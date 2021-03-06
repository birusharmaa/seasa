<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Users_model;
use App\Models\Enquiry_model;

class SettingsController extends BaseController
{
    Protected $session_check;
    public function __construct()
    {
        $request = \Config\Services::request();
        $session = \Config\Services::session();
        $this->session = $session;
        $this->request = $request;
        $model = new Enquiry_model();
        $this->enquiry = $model;
        helper("general");
       
        if(!$this->session->has('login_user')){
            echo view('login');
            exit;
        }
    }

    public function index()
    {   
        $model = new Users_model();
        if($this->session->has('login_user')){
            $user_data = $this->session->get('login_user');
        }       
        $userinfo =  $model->getUserInfoId($user_data['user_id']);
        $data['userInfo'] =  $userinfo;   
        $data['color'] =  getThemeColor($user_data["user_id"]);       
        $data['title'] = "Account Settings";  
        

        return view('Dashboard/settings',$data);
    }

    public function profile()
    {
        
        $model = new Users_model();
        if($this->session->has('login_user')){
            $user_data = $this->session->get('login_user');
        }       
        $userinfo =  $model->getUserInfoId($user_data['user_id']);
        $data['color'] =  getThemeColor($user_data["user_id"]);       
        $data['userInfo'] =  $userinfo; 
        $data['title'] = "Profile";
        return view('Dashboard/profile',$data);
    }

    public function dashboard(){        

        $model = new Users_model();
        if($this->session->has('login_user')){
            $user_data = $this->session->get('login_user');
        }       
        $userinfo =  $model->getUserInfoId($user_data['user_id']);
        $data['enquiry']  = $this->enquiry->get_TopFiveEnquiry();
        $data['count']  = $this->enquiry->get_totalCount();
        $data['userInfo'] =  $userinfo; 
        $data['color'] =  getThemeColor($user_data["user_id"]);       
        $data['title'] = "Dashboard";
        
        return view('Dashboard/index',$data);
    }

    public function save_general($id){
        $model = new Users_model();        
        $data =  array(
            'user_name' =>  xss_clean($this->request->getVar('user_name')),  
            'user_email' =>  xss_clean($this->request->getVar('user_email')),   
            'user_phone' =>  xss_clean($this->request->getVar('user_phone'))
        );    
        $return = $model->UpdateUser($id,$data); 
        if($return){
            echo json_encode(['status'=>true,'message'=>'General Information Update Successfully.']);
        }else{
            echo json_encode(['status'=>false,'message'=>'Their is some problem. Please try again.']);
        }
        
    }

    public function updateSiteLogo($id)
    {          
        $model = new Users_model();
        $file_name = rand() . $_FILES['company_logo']['name'];       
        $filewithpath = "/assets/img/logo/" . $file_name;        
        $file = $this->request->getFile('company_logo');
        $file->move('./assets/img/logo', $file_name);
        $return = $model->uploadImage($id,$filewithpath); 
        if($return){
            echo json_encode(['status'=> true, 'path'=>$filewithpath]);
        }else{
            return false;
        }  
        
    }

    function reset_password($id)
    {       
        $model = new Users_model();
        $password = $_POST['password']; 
        $user_data = array("user_password" => MD5($password));
        $return = $model->UpdateUser($id,$user_data); 
        if($return){
            echo json_encode(['status'=>true,'message'=>'Password Change Successfully']);
        }else{
            echo json_encode(['status'=>false,'message'=>'Their is some problem. Please try again.']);
        }
 
    }

    function compony_Info($id){
        $model = new Users_model();         
        $data =  array(
            'company_name' =>  xss_clean($this->request->getVar('company_name')),
            'company_profile' =>  xss_clean($this->request->getVar('company_profile')),
            'company_address' =>  xss_clean($this->request->getVar('company_address')),
            'company_phone_no' =>  xss_clean($this->request->getVar('company_phone_no')),
            'website_URL' =>   xss_clean($this->request->getVar('website_URL'))
        );    
        $return = $model->UpdateUser($id,$data); 
        if($return){
            echo json_encode(['status'=>true,'message'=>'Company Info Update Successfully.']);
        }else{
            echo json_encode(['status'=>false,'message'=>'Their is some problem. Please try again.']);
        }
    }

    function social_link($id){
        $model = new Users_model();   
        $data =  array(
            'facebook_link' => xss_clean($this->request->getVar('facebook_link')), 
            'twitter_link' => xss_clean($this->request->getVar('twitter_link')), 
            'google_plus' => xss_clean($this->request->getVar('google_plus')), 
            'linkedIn' =>  xss_clean($this->request->getVar('linkedIn'))          
        );    
        $return = $model->UpdateUser($id,$data); 
        if($return){
            echo json_encode(['status'=>true,'message'=>'Social Links Update Successfully.']);
        }else{
            echo json_encode(['status'=>false,'message'=>'Their is some problem. Please try again.']);
        }
    }

    function save_personalinfo($id){
        $model = new Users_model();          
        $data =  array(
            'user_name' =>  xss_clean($this->request->getVar('user_name')),
            'user_email' =>  xss_clean( $this->request->getVar('user_email')),
            'user_phone' =>  xss_clean($this->request->getVar('user_phone'))
        );    
        $return = $model->UpdateUser($id,$data); 
        if($return){
            echo json_encode(['status'=>true,'message'=>'Personal Informaltion Update Successfully.']);
        }else{
            echo json_encode(['status'=>false,'message'=>'Their is some problem. Please try again.']);
        }
    }

    function update_theme($color){
        $model = new Users_model(); 
        if($this->session->has('login_user')){
            $user_data = $this->session->get('login_user');
        }  
        $return = $model->update_theme($user_data['user_id'],$color); 
        if($return){
            echo json_encode(['status'=>true,'message'=>'Theme Color update Successfully']);
        }else{
            echo json_encode(['status'=>false,'message'=>'Their is some problem. Please try again.']);
        }
    }

  

}
