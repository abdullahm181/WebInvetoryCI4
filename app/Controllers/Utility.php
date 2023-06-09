<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modeluser;
use App\Models\Modelbarang;
use Ifsnop\Mysqldump\Mysqldump;

class Utility extends BaseController
{
    public function index()
    {
        return view('utility/index');
    }

    public function scannerPage()
    {
        return view('utility/scanner');
    }

    public function doBackup()
    {
        try {
            $tglSekarang = date('d-m-Y');

            $dump = new Mysqldump('mysql:host=localhost;dbname=inventoryweb_ci4;port=3306', 'root', '');
            $dump->start('database/backup/inventoryweb_ci4-' . $tglSekarang . '.sql');

            $pesan = 'Backup Database Berhasil';
            session()->setFlashdata('pesan', $pesan);
            return redirect()->to('/utility/index');
        } catch (\Exception $e) {
            $pesan = "mysqldump-php error: " . $e->getMessage();
            session()->setFlashdata('pesan', $pesan);
            return redirect()->to('/utility/index');
        }
    }
    public function ambilDataBarang()
    {
        if ($this->request->isAjax()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $modelBarang = new Modelbarang();
            $ambilData = $modelBarang->get_by_kode($kodebarang);

            if ($ambilData == NULL) {
                $json = [
                    'error' => 'Data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $ambilData['brgnama'],
                    'hargajual' => $ambilData['brgharga'],
                    'brgstok'=>$ambilData['brgstok'],
                    'brglokasi'=>$ambilData['loklorong'].' '.$ambilData['lokrak'],
                    'brggambar'=>$ambilData['brggambar']
                ];

                $json = [
                    'sukses' => $data
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }
    public function gantipassword()
    {
        return view('utility/formgantipassword');
    }

    public function updatepassword()
    {
        if ($this->request->isAjax()) {
            $iduser = session()->get('userid');
            $passlama = $this->request->getVar('passlama');
            $passbaru = $this->request->getVar('passbaru');
            $confirmpass = $this->request->getPost('confirmpass');

            $validation = \Config\Services::validation();

            $valid = $this->validate([
                'passlama' => [
                    'label' => 'Password Lama',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'passbaru' => [
                    'label' => 'Password Baru',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                    ]
                ],
                'confirmpass' => [
                    'label' => 'Confirm Password Baru',
                    'rules' => 'required|matches[passbaru]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong',
                        'matches' => '{field} tidak sama dengan password baru',
                    ]
                ],
            ]);

            if (!$valid) {
                $json = [
                    'error' => [
                        'errorPassLama' => $validation->getError('passlama'),
                        'errorPassBaru' => $validation->getError('passbaru'),
                        'errorConfirmPass' => $validation->getError('confirmpass'),
                    ]
                ];
            } else {
                $modelUser = new Modeluser();
                $rowData = $modelUser->find($iduser);
                $passUser = $rowData['userpassword'];

                if (password_verify($passlama, $passUser)) {
                    $hash = password_hash($passbaru, PASSWORD_DEFAULT);
                    $modelUser->update($iduser, [
                        'userpassword' => $hash
                    ]);

                    $json = [
                        'sukses' => 'Password berhasil diubah'
                    ];
                } else {
                    $json = [
                        'error' => [
                            'errorPassLama' => 'Password Lama tidak sesuai',
                        ]
                    ];
                }
            }

            echo json_encode($json);
        }
    }
}
