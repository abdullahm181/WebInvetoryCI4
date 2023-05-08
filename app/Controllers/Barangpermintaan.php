<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\Modelbarangpermintaan;
use App\Models\Modeltempbarangpermintaan;
use App\Models\Modeldetailbarangpermintaan;

class Barangpermintaan extends BaseController
{
    public function index()
    {
        return view('barangpermintaan/forminput');
    }
    public function buatNoSuratJalan()
    {
        $tanggalSekarang = $this->request->getVar('tanggal');
        if($tanggalSekarang=='' || $tanggalSekarang==null)
        $tanggalSekarang = date('Y-m-d');
        $modelBarangPermintaan = new Modelbarangpermintaan();

        $hasil = $modelBarangPermintaan->noFakturOtomatis($tanggalSekarang);
        // $data = $hasil['nofaktur'];

        // $lastNoUrut = substr($data, -4);
        // nomor urut ditambah 1
        $nextNoUrut = intval($hasil) + 1;
        // membuat format nomor transaksi berikutnya
        $noFaktur = 'FSJ'.date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);

        $json = [
            'nofaktur' => $noFaktur
        ];

        echo json_encode($json);
    }
    public function dataTemp()
    {
        
        if ($this->request->isAjax()) {
            $nosuratjalan = $this->request->getPost('nosuratjalan');

            $modelTemp = new Modeltempbarangpermintaan();
            //print_r( $modelTemp->tampilDataTemp($nosuratjalan));
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($nosuratjalan)
            ];
    
            $json = [
                'data' => view('barangpermintaan/datatemp', $data)
            ];
            
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function ambilDataBarang()
    {
        if ($this->request->isAjax()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $modelBarang = new Modelbarang();
            $ambilData = $modelBarang->where('brgkode', $kodebarang)->first();

            if ($ambilData == NULL) {
                $json = [
                    'error' => 'Data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $ambilData['brgnama'],
                    'hargajual' => $ambilData['brgharga'],
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

    public function simpanTemp()
    {
        if ($this->request->isAjax()) {
            $nosuratjalan = $this->request->getPost('nosuratjalan');
            $modelBarangMasuk= new Modelbarangpermintaan();
            $cekFaktur=$modelBarangMasuk->find($nosuratjalan);
            if($cekFaktur!=null) exit('Maaf tidak bisa diproses, nosuratjalan sudah ada');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');
            //print_r($kodebarang);//udah bener
            $modelTempBarang = new Modeltempbarangpermintaan();
            $modelTempBarang->insert([
                'detnosuratjalan' => $nosuratjalan,
                'detbrgkode' => $kodebarang,
                'detjml' => $jumlah
            ]);

            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function hapus()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->getPost('id');

            $modelTempBarang = new Modeltempbarangpermintaan();
            $modelTempBarang->delete($id);

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function cariDataBarang()
    {
        if ($this->request->isAjax()) {
            $json = [
                'data' => view('barangpermintaan/modalcaribarang')
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    

    public function detailCariBarang()
    {
        if ($this->request->isAjax()) {
            $cari = $this->request->getPost('cari');

            $modelBarang = new Modelbarang();

            $data = $modelBarang->tampildata_cari($cari)->get();

            if ($data != null) {
                $json = [
                    'data' => view('barangpermintaan/detaildatabarang', [
                        'tampildata' => $data
                    ])
                ];

                echo json_encode($json);
            }
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function selesaiTransaksi()
    {
        if ($this->request->isAjax()) {
            $nosuratjalan = $this->request->getPost('nosuratjalan');
            $tglpermintaan = $this->request->getPost('tglpermintaan');

            $modelTemp = new Modeltempbarangpermintaan();
            $dataTemp = $modelTemp->getWhere(['detnosuratjalan' => $nosuratjalan]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'Maaf, data item untuk nosuratjalan ini belum ada'
                ];
            } else {
                // simpan ke tabel barang permintaan
                $modelBarangMasuk = new Modelbarangpermintaan();

                $modelBarangMasuk->insert([
                    'nosuratjalan' => $nosuratjalan,
                    'tglpermintaan' => $tglpermintaan,
                    'status'=> 'waiting',
                    'inputby' => session()->get('userid')
                ]);

                // simpan ke tabel detail barang masuk
                $modelDetailBarangMasuk = new Modeldetailbarangpermintaan();
                
                foreach ($dataTemp->getResultArray() as $row) :
                    $modelDetailBarangMasuk->insert([
                        'detnosuratjalan' => $row['detnosuratjalan'],
                        'detbrgkode' => $row['detbrgkode'],
                        'detjml' => $row['detjml']
                    ]);
                endforeach;

                // hapus data di tabel temporari
                $modelTemp->emptyTable();

                $json = [
                    'sukses' => 'Transaksi berhasil disimpan'
                ];
            }

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function data()
    {
        $dataBarang = new Modelbarangpermintaan();
        $data = [
            'databarang' => $dataBarang->findAll()
        ];
        return view('barangpermintaan/viewdata', $data);
    }

    public function detailItem()
    {
        if ($this->request->isAjax()) {
            $nosuratjalan = $this->request->getPost('nosuratjalan');
            $modelDetail = new Modeldetailbarangpermintaan();

            $data = [
                'tampildatadetail' => $modelDetail->dataDetail($nosuratjalan)
            ];

            $json = [
                'data' => view('barangpermintaan/modaldetailitem', $data)
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function edit($nosuratjalan)
    {
        $modelBarangMasuk = new Modelbarangpermintaan();
        $cekFaktur = $modelBarangMasuk->cekFaktur($nosuratjalan);
        //print_r($cekFaktur );
        if (count($cekFaktur) > 0) {
            $row = $cekFaktur[0];

            $data = [
                'nonosuratjalan' => $row->nosuratjalan,
                'tanggal' => $row->tglpermintaan
            ];

            return view('barangpermintaan/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function dataDetail()
    {
        if ($this->request->isAjax()) {
            $nosuratjalan = $this->request->getPost('nosuratjalan');

            $modelDetail = new Modeldetailbarangpermintaan();

            $data = [
                'datadetail' => $modelDetail->dataDetail($nosuratjalan),
                'nosuratjalan'=>$nosuratjalan
            ];

            
            $json = [
                'data' => view('barangpermintaan/datadetail', $data),
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function editItem()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('iddetail');

            $modelDetail = new Modeldetailbarangpermintaan();
            $ambilData = $modelDetail->ambilDetailBerdasarkanID($iddetail);
            //print_r($ambilData->getRowArray());

            $row = $ambilData->getRowArray();

            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
                'hargajual' => $row['brgharga'],
                'jumlah' => $row['detjml']
            ];

            $json = [
                'sukses' => $data
            ];
            echo json_encode($json);
            
        }
    }

    public function simpanDetail()
    {
        if ($this->request->isAjax()) {
            $nosuratjalan = $this->request->getPost('nosuratjalan');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetail = new Modeldetailbarangpermintaan();
            $modelBarangMasuk = new Modelbarangpermintaan();

            $modelDetail->insert([
                'detnosuratjalan' => $nosuratjalan,
                'detbrgkode' => $kodebarang,
               
                'detjml' => $jumlah,
                
            ]);
            

            $json = [
                'sukses' => 'Item berhasil ditambahkan'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function updateItem()
    {
        if ($this->request->isAjax()) {
            $iddetail = $this->request->getPost('iddetail');
            $nosuratjalan = $this->request->getPost('nosuratjalan');
           
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');
            

            $modelDetail = new Modeldetailbarangpermintaan();
            $modelBarangMasuk = new Modelbarangpermintaan();
            

            //update table detailbarang masuk
            $modelDetail->update($iddetail, [
                
                'detjml' => $jumlah,

            ]);

            $json = [
                'sukses' => 'Item berhasil diupdate'
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function hapusItemDetail()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->getPost('id');
            $nosuratjalan = $this->request->getPost('nosuratjalan');

            $modelDetail = new Modeldetailbarangpermintaan();
            $modelBarangMasuk = new Modelbarangpermintaan();
            
            $modelDetail->delete($id);

           
            

            $json = [
                'sukses' => 'Item berhasil dihapus'
            ];

            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function hapusTransaksi()
    {
        $nosuratjalan = $this->request->getPost('nosuratjalan');

        $db = \Config\Database::connect();
        $modelBarangMasuk = new Modelbarangpermintaan();
        $modelBarang= new Modelbarang();
        
        $db->table('detail_barangpermintaan')->delete(['detnosuratjalan' => $nosuratjalan]);
        $modelBarangMasuk->delete($nosuratjalan);

        $json = [
            'sukses' => 'Data transaksi berhasil dihapus'
        ];

        echo json_encode($json);
    }
}
