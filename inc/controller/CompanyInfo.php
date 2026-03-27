<?php 
/**
 * @author Hieu "JIN" Phan Trung
 * * Controller: Company Information
 */
namespace gpweb\inc\controller;
class CompanyInfo {
  private static CompanyInfo $instance;
  private string $address;
  private string $email;
  private string $phone;
  private array $socials;
  public static function getInstance(): CompanyInfo {
    if( !isset( self::$instance ) ) {
      self::$instance = new CompanyInfo();
    }
    return self::$instance;
  }
  public function register() {
    if( !function_exists('get_field') ) {
      error_log('ACF function get_field does not exist. CompanyInfo controller cannot be initialized.');
      return;
    }
    $companyInfo = get_field('company_information', 'gpw_settings');
    $this->address = $companyInfo['address'] ?? '';
    $this->email = $companyInfo['email'] ?? '';
    $this->phone = $companyInfo['phone_number'] ?? '';
    $this->socials = $companyInfo['social'] ?? [];
  }
  public function getAddress() {
    return $this->address;
  }
  public function getPhoneNumber() {
    return $this->phone;
  }
}