<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\Modelbarangmasuk;
use App\Models\Modeltempbarangmasuk;
use App\Models\Modeldetailbarangmasuk;

class Barangmasuk extends BaseController
{

    public function index()
    {
        return view('barangmasuk/forminput');
    }

    public function buatfaktur()
    {
        $tanggalSekarang = $this->request->getVar('tanggal');
        if($tanggalSekarang=='' || $tanggalSekarang==null)
        $tanggalSekarang = date('Y-m-d');
        $modelBarangPermintaan = new Modelbarangmasuk();

        $hasil = $modelBarangPermintaan->noFakturOtomatis($tanggalSekarang);
        // $data = $hasil['nofaktur'];

        // $lastNoUrut = substr($data, -4);
        // nomor urut ditambah 1
        $nextNoUrut = intval($hasil) + 1;
        // membuat format nomor transaksi berikutnya
        $noFaktur = 'FM'.date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);

        $json = [
            'nofaktur' => $noFaktur
        ];

        echo json_encode($json);
    }

    public function dataTemp()
    {
        if ($this->request->isAjax()) {
            $faktur = $this->request->getPost('faktur');

            $modelTemp = new Modeltempbarangmasuk();
            $data = [
                'datatemp' => $modelTemp->tampilDataTemp($faktur)
            ];

            $json = [
                'data' => view('barangmasuk/datatemp', $data)
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
                    'namabarang' => $ambilData['brgnama']
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
            $faktur = $this->request->getPost('faktur');
            $modelBarangMasuk= new Modelbarangmasuk();
            $cekFaktur=$modelBarangMasuk->find($faktur);
            if($cekFaktur!=null) exit('Maaf tidak bisa diproses, faktur sudah ada');
           
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');
            //print_r($kodebarang);//udah bener
            $modelTempBarang = new Modeltempbarangmasuk();
            $modelTempBarang->insert([
                'detfaktur' => $faktur,
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

            $modelTempBarang = new Modeltempbarangmasuk();
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
                'data' => view('barangmasuk/modalcaribarang')
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

            $data = $modelBarang->tampildata_cari($cari);

            if ($data != null) {
                $json = [
                    'data' => view('barangmasuk/detaildatabarang', [
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
            $faktur = $this->request->getPost('faktur');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $nosuratjalan  = $this->request->getPost('nosuratjalan');

            $modelTemp = new Modeltempbarangmasuk();
            $dataTemp = $modelTemp->getWhere(['detfaktur' => $faktur]);

            if ($dataTemp->getNumRows() == 0) {
                $json = [
                    'error' => 'Maaf, data item untuk faktur ini belum ada'
                ];
            } else {
                // simpan ke tabel barang masuk
                $modelBarangMasuk = new Modelbarangmasuk();
                

                $modelBarangMasuk->insert([
                    'faktur' => $faktur,
                    'tglfaktur' => $tglfaktur,
                    'nosuratjalan'=> $nosuratjalan ,
                    'inputby' => session()->get('userid')
                ]);

                // simpan ke tabel detail barang masuk
                $modelDetailBarangMasuk = new Modeldetailbarangmasuk();
                
                foreach ($dataTemp->getResultArray() as $row) :
                    $modelDetailBarangMasuk->insert([
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'detjml' => $row['detjml'],
                    ]);

                    //update stock barang 
                    $modelBarang = new Modelbarang();
                    $data = $modelBarang->get_by_kode($row['detbrgkode']);
                    $data['brgstok']=$data['brgstok']+$row['detjml'];
                    $update =$modelBarang->update($data['brgid'], $data);
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
        $dataBarang = new Modelbarangmasuk();
        $data = [
            'databarang' => $dataBarang->findAll()
        ];
        return view('barangmasuk/viewdata', $data);
    }

    public function detailItem()
    {
        if ($this->request->isAjax()) {
            $faktur = $this->request->getPost('faktur');
            $modelDetail = new Modeldetailbarangmasuk();

            $data = [
                'tampildatadetail' => $modelDetail->dataDetail($faktur)
            ];

            $json = [
                'data' => view('barangmasuk/modaldetailitem', $data)
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }

    public function edit($faktur)
    {
        $modelBarangMasuk = new Modelbarangmasuk();
        $cekFaktur = $modelBarangMasuk->cekFaktur($faktur);
        //print_r($cekFaktur );
        if (count($cekFaktur) > 0) {
            $row = $cekFaktur[0];

            $data = [
                'nofaktur' => $row->faktur,
                'tanggal' => $row->tglfaktur,
                'nosuratjalan'=>$row->nosuratjalan
            ];

            return view('barangmasuk/formedit', $data);
        } else {
            exit('Data tidak ditemukan');
        }
    }

    public function dataDetail()
    {
        if ($this->request->isAjax()) {
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();
            //print_r($modelDetail->dataDetail($faktur));
            $data = [
                'datadetail' => $modelDetail->dataDetail($faktur),
            ];

            $json = [
                'data' => view('barangmasuk/datadetail', $data)
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

            $modelDetail = new Modeldetailbarangmasuk();
            $ambilData = $modelDetail->ambilDetailBerdasarkanID($iddetail);
            //print_r($ambilData->getRowArray());

            $row = $ambilData->getRowArray();

            $data = [
                'kodebarang' => $row['detbrgkode'],
                'namabarang' => $row['brgnama'],
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
            $faktur = $this->request->getPost('faktur');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();

            $modelDetail->insert([
                'detfaktur' => $faktur,
                'detbrgkode' => $kodebarang,
                'detjml' => $jumlah,
            ]);
            //update stock barang 
            $modelBarang = new Modelbarang();
            $data = $modelBarang->get_by_kode($kodebarang);
            $data['brgstok']=$data['brgstok']+$jumlah;
            $update =$modelBarang->update($data['brgid'], $data);

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
            $faktur = $this->request->getPost('faktur');
            $kodebarang = $this->request->getPost('kodebarang');
            $jumlah = $this->request->getPost('jumlah');

            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();
            //get old detailbarang masuk
            $ambilData = $modelDetail->ambilDetailBerdasarkanID($iddetail);
            //print_r($ambilData->getRowArray());

            $row = $ambilData->getRowArray();
            //revisi stock pada barang
            //update stock barang 
            $modelBarang = new Modelbarang();
            $data = $modelBarang->get_by_kode($row['detbrgkode']);
            $data['brgstok']=($data['brgstok']-$row['detjml'])+$jumlah;
            $update =$modelBarang->update($data['brgid'], $data);

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
            $faktur = $this->request->getPost('faktur');

            $modelDetail = new Modeldetailbarangmasuk();
            $modelBarangMasuk = new Modelbarangmasuk();
            $dataOld=$modelDetail->find($id);
            //print_r($dataOld['detbrgkode']);
            $modelBarang = new Modelbarang();
            $data = $modelBarang->get_by_kode($dataOld['detbrgkode']);
            $data['brgstok']=($data['brgstok']-$dataOld['detjml']);
            $update =$modelBarang->update($data['brgid'], $data);
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
        $faktur = $this->request->getPost('faktur');

        $db = \Config\Database::connect();
        $modelBarangMasuk = new Modelbarangmasuk();
        $modelBarang= new Modelbarang();
        //get detail old brg masuk
        $dataDetOld=$db->table('detail_barangmasuk')->where('detfaktur',$faktur)->get()->getResult();

        foreach($dataDetOld as $dt){
            
            $dataBarang=$modelBarang->where('brgkode',$dt->detbrgkode)->first();
            $dataBarang['brgstok']=$dataBarang['brgstok']-$dt->detjml;
            $modelBarang->update($dataBarang['brgid'], $dataBarang);
        }
        $db->table('detail_barangmasuk')->delete(['detfaktur' => $faktur]);
        $modelBarangMasuk->delete($faktur);

        $json = [
            'sukses' => 'Data transaksi berhasil dihapus'
        ];

        echo json_encode($json);
    }
}
