<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modeluser;

use Config\Services;

class Users extends BaseController
{
    public function index()
    {
        return view('users/data');
    }

    public function listData()
    {
        $request = Services::request();
        $db = \Config\Database::connect();
            $builder = $db->table('users')->select('userid, usernama,usernamalengkap, levelnama, useraktif, userlevelid')
                ->join('levels', 'levelid = userlevelid')->get()->getResult();
            $data = [];
            $no = $request->getPost("start");
            foreach ($builder as $list) {
                $no++;
                $row = [];
                if ($list->useraktif == '1') {
                    $status= '<span class="badge badge-success">Aktif</span>';
                } else {
                    $status= '<span class="badge badge-danger">Tidak Aktif</span>';
                }
                $tombolPilih = "";
                if ($list->userlevelid != '1') {
                    $tombolPilih = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"view('" . $list->userid . "')\"> View </button>";
                }
                


                $row[] = $no;
                $row[] = $list->usernama;
                $row[] = $list->usernamalengkap;
                $row[] = $list->levelnama;
                $row[] =  $status;
                $row[] = $tombolPilih;
                $data[] = $row;
            }
            $output = [
                "draw" =>  $request->getPost('draw'),
                "recordsTotal" => count($builder),
                "recordsFiltered" => count($builder),
                "data" => $data
            ];
            echo json_encode($output);
    }

    public function formtambah()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();

            $data = [
                'datalevel' => $db->table('levels')->where('levelid !=', '1')->get(),
            ];

            return view('users/modaltambah', $data);
        }
    }

    public function simpan()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            $namalengkap = $this->request->getVar('namalengkap');
            $level = $this->request->getVar('level');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'iduser' => [
                    'rules' => 'required|is_unique[users.usernama]',
                    'label' => 'ID User',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'is_unique' => '{field} sudah terdaftar'
                    ]
                ],
                'namalengkap' => [
                    'rules' => 'required',
                    'label' => 'Nama Lengkap',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'level' => [
                    'rules' => 'required',
                    'label' => 'Level',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ]
            ]);

            if (!$valid) {
                $json = [
                    'error' => [
                        'errorIdUser' => $validation->getError('iduser'),
                        'errorNamaLengkap' => $validation->getError('namalengkap'),
                        'errorLevel' => $validation->getError('level'),
                    ]
                ];
            } else {
                $modelUser = new Modeluser();
                $modelUser->insert([
                    'usernama' => $iduser,
                    'usernamalengkap' => $namalengkap,
                    'userlevelid' => $level,
                ]);

                $json = [
                    'sukses' => 'Data berhasil ditambahkan',
                ];
            }

            echo json_encode($json);
        }
    }

    public function formedit()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('userid');
            $modelUser = new Modeluser();
            $rowUser = $modelUser->find($iduser);

            if ($rowUser) {
                $db = \Config\Database::connect();

                $data = [
                    'datalevel' => $db->table('levels')->where('levelid !=', '1')->get(),
                    'iduser' => $iduser,
                    'usernama' => $rowUser['usernama'],
                    'namalengkap' => $rowUser['usernamalengkap'],
                    'level' => $rowUser['userlevelid'],
                    'status' => $rowUser['useraktif'],
                ];
            }

            return view('users/modaledit', $data);
        }
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            //$usernama=$this->request->getVar('usernama');
            $namalengkap = $this->request->getVar('namalengkap');
            $level = $this->request->getVar('level');

            $modelUser = new Modeluser();
            $modelUser->update($iduser, [
                'userid' => $iduser,
                'usernamalengkap' => $namalengkap,
                'userlevelid' => $level,
            ]);

            $json = [
                'sukses' => 'Data berhasil diupdate',
            ];

            echo json_encode($json);
        }
    }

    public function updateStatus()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            $modelUser = new Modeluser();
            $rowUser = $modelUser->find($iduser);

            $userAktif = $rowUser['useraktif'];

            if ($userAktif == '1') {
                $modelUser->update($iduser, [
                    'useraktif' => '0',
                ]);
            } else {
                $modelUser->update($iduser, [
                    'useraktif' => '1',
                ]);
            }

            $json = [
                'sukses' => ''
            ];

            echo json_encode($json);
        }
    }

    public function hapus()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');
            $modelUser = new Modeluser();
            $modelUser->delete($iduser);

            $json = [
                'sukses' => 'Data berhasil dihapus',
            ];

            echo json_encode($json);
        }
    }

    public function resetPassword()
    {
        if ($this->request->isAJAX()) {
            $iduser = $this->request->getVar('iduser');

            $modelUser = new Modeluser();
            $passRandom = rand(1, 99999);

            $passHashBaru = password_hash($passRandom, PASSWORD_DEFAULT);

            $modelUser->update($iduser, [
                'userpassword' => $passHashBaru,
            ]);

            $json = [
                'sukses' => '',
                'passwordBaru' => $passRandom,
            ];

            echo json_encode($json);
        }
    }
}
