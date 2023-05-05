<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelauth;

class Auth extends BaseController
{
    public function index()
    {
        return view('auth/index');
    }
    public function cekUser(){
        $session = session();
        $usernama=$this->request->getPost('usernama');
        $userpassword=$this->request->getVar('userpassword');

        $validation=\Config\Services::validation();

        $valid=$this->validate([
            'usernama'=>[
                'label'=>'Usernama',
                'rules' => 'required',
                'errors'=>['required'=> '{field} tidak boleh kosong']
            ],
            'userpassword'=>[
                'label'=>'Password',
                'rules' => 'required',
                'errors'=>['required'=> '{field} tidak boleh kosong']
            ]
        ]);

        if(!$valid){
            $sessError=[
                'errUserNama'=>$validation->getError('usernama'),
                'errPassword'=>$validation->getError('userpassword')
            ];

            session()->setFlashdata($sessError);
            return redirect()->to(site_url('Auth/index'));
        }else{
            $modelauth=new Modelauth();
            $cekUserLogin = $modelauth->where('usernama', $usernama)->first();
            //$cekUserLogin=$modelauth->find($usernama);
            if($cekUserLogin){
                $passwordUser= $cekUserLogin['userpassword'];
                if(password_verify($userpassword,$passwordUser)){
                    $simpan_session=[
                        'userid'=>$cekUserLogin['userid'],
                        'usernama'=>$cekUserLogin['usernama'],
                        'userlevelid'=>$cekUserLogin['userlevelid']
                    ];
                    session()->set($simpan_session);
                    return redirect()->to('/main/index');
                }else{
                    $sessError=[
                        'errPassword'=>'Password anda salah'
                    ];
        
                    session()->setFlashdata($sessError);
                    return redirect()->to(site_url('Auth/index'));
                }
            }else{
                
                $sessError=[
                    'errUserNama'=>'Maaf user tidak terdaftar'
                ];
    
                session()->setFlashdata($sessError);
                return redirect()->to(site_url('Auth/index'));
            }
        }
    }

    public function keluar(){
        session()->destroy();
        return redirect()->to('/auth/index');
    }
}
