import { defineConfig } from 'vite';
import path from 'path';
import fs from 'fs';
import dotenv from 'dotenv';
import autoprefixer from 'autoprefixer';
import sftp from 'ssh2-sftp-client';

dotenv.config();

const SCRIPT_TYPE = 'js';
const STYLE_TYPE = 'scss';

// ------------------------------
//  Script & Style Entries
// ------------------------------
const scriptEntries = fs
  .readdirSync(path.resolve(__dirname, 'src/js/export'))
  .filter(file => file.endsWith('.js'))
  .map(file => file.replace('.js', ''));

if (scriptEntries.length > 1) {
  // remove _empty entry if there are other script entries
  const emptyIndex = scriptEntries.indexOf('_empty');
  if (emptyIndex > -1) {
    scriptEntries.splice(emptyIndex, 1);
  }
}

const scssEntries = fs
  .readdirSync(path.resolve(__dirname, 'src/scss/export'))
  .filter(file => file.endsWith('.scss'))
  .map(file => file.replace('.scss', ''));

const generateInputs = (entries, type) => {
  const input = {};
  entries.forEach(name => {
    input[`${type}:${name}`] = path.resolve(__dirname, `src/${type}/export/${name}.${type}`);
  });
  return input;
};

// ------------------------------
// SFTP Upload Logic (same as gulp-sftp-up4)
// ------------------------------
const uploadDirToSFTP = async (localDir, remoteDir) => {
  const client = new sftp();

  await client.connect({
    host: process.env.HOST,
    username: process.env.USERNAME,
    password: process.env.PASSWORD,
    port: process.env.PORT,
  });

  const uploadRecursive = async (localPath, remotePath) => {
    const stat = fs.statSync(localPath);
    // Ignore .DS_Store files
    if (path.basename(localPath) === '.DS_Store') return;
    if (stat.isDirectory()) {
      // Ensure remote directory exists
      try { await client.mkdir(remotePath, true); } catch (e) {}
      const files = fs.readdirSync(localPath);
      for (const file of files) {
        await uploadRecursive(
          path.join(localPath, file),
          `${remotePath}/${file}`
        );
      }
    } else {
      // Check if remote file exists and is identical
      let skip = false;
      try {
        const stat = await client.stat(remotePath);
        const localSize = fs.statSync(localPath).size;
        const remoteSize = stat.size;
        if (localSize === remoteSize) {
          skip = true; // identical → skip upload
        }
      } catch (e) {
        // file does not exist on remote → upload it
      }
      if (!skip) {
        // Ensure parent directory exists
        const remoteDirPath = path.dirname(remotePath);
        try { await client.mkdir(remoteDirPath, true); } catch (e) {}
        await client.put(localPath, remotePath);
        console.log(`✔ Uploaded: ${remotePath}`);
      } else {
        // console.log(`↪ Skipped (unchanged): ${remotePath}`);
      }
    }
  };

  await uploadRecursive(localDir, remoteDir);
  await client.end();
};

// ------------------------------
// Vite Config
// ------------------------------
const config = defineConfig({
  root: './src',
  build: {
    outDir: '../assets',
    emptyOutDir: false,
    sourcemap: false,
    cssDevSourcemap: true,

    rollupOptions: {
      input: {
        ...(scriptEntries.length > 0 ? generateInputs(scriptEntries, SCRIPT_TYPE) : { empty: path.resolve(__dirname, 'src/js/export/_empty.js') }),
        ...generateInputs(scssEntries, STYLE_TYPE),
      },

      output: {
        manualChunks: undefined,
        entryFileNames: info => {
          const name = info.name
            .replace(/^js[:_]/, '')
            .replace(/^scss[:_]/, '')
            .replace(/(\.min\d*)?$/, '');
          return `js/${name}.min.js`;
        },

        chunkFileNames: chunkInfo => {
          const version = '0.0.2';
          return `js/components/${chunkInfo.name}-chunk-v${version}.min.js`;
        },

        assetFileNames: assetInfo => {
          if (assetInfo.name.endsWith('.css')) {
            const clean = assetInfo.name
              .replace(/^js[:_]/, '')
              .replace(/^scss[:_]/, '')
              .replace('.css', '');
            return `css/${clean}.min.css`;
          }
          return '[name].[ext]';
        },
      },
    },
  },

  // --------------------------------
  //          SCSS Compilation
  // --------------------------------
  css: {
    devSourcemap: true,
    preprocessorOptions: {
      scss: {
        // replicate your import style
        additionalData: `@use "../abstracts/" as *;`,
      },
    },
    postcss: {
      plugins: [autoprefixer()],
    },
  },

  // --------------------------------
  // Plugins
  // --------------------------------
  plugins: [
    {
      name: 'sftp-upload',
      apply: 'build',
      closeBundle: async () => {
        console.log('🚀 Uploading assets to server...\n');

        await uploadDirToSFTP(path.resolve(__dirname, 'assets/css'), `${process.env.REMOTE_PATH}/assets/css`);

        await uploadDirToSFTP(path.resolve(__dirname, 'assets/js'), `${process.env.REMOTE_PATH}/assets/js`);

        console.log('🎉 Upload completed!');
      },
    },
  ],
});

export default config;
