<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Modelbarang;
use App\Models\ModelBarangKeluar;

use App\Models\ModelDetailBarangKeluar;

use App\Models\ModelTempBarangKeluar;
use Config\Services;

class Barangkeluar extends BaseController
{
    public function index()
    {
        //
    }
    private function buatFaktur()
    {
        $tanggalSekarang = date('Y-m-d');
        $modelBarangKeluar = new ModelBarangKeluar();

        $hasil = $modelBarangKeluar->noFakturOtomatis($tanggalSekarang);
        // $data = $hasil['nofaktur'];

        // $lastNoUrut = substr($data, -4);
        // nomor urut ditambah 1
        $nextNoUrut = intval($hasil) + 1;
        // membuat format nomor transaksi berikutnya
        $noFaktur = 'FK'.date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);
        return $noFaktur;
    }
    public function buatNoFaktur()
    {
        $tanggalSekarang = $this->request->getVar('tanggal');
        $modelBarangKeluar = new ModelBarangKeluar();

        $hasil = $modelBarangKeluar->noFakturOtomatis($tanggalSekarang);
        // $data = $hasil['nofaktur'];

        // $lastNoUrut = substr($data, -4);
        // nomor urut ditambah 1
        $nextNoUrut = intval($hasil) + 1;
        // membuat format nomor transaksi berikutnya
        $noFaktur = 'FK'.date('dmy', strtotime($tanggalSekarang)) . sprintf('%04s', $nextNoUrut);

        $json = [
            'nofaktur' => $noFaktur
        ];

        echo json_encode($json);
    }

    public function data()
    {
        return view('barangkeluar/viewdata');
    }

    public function listData()
    {
        $tglawal = $this->request->getPost('tglawal');
        $tglakhir = $this->request->getPost('tglakhir');

        $request = Services::request();
        $datamodel = new ModelBarangKeluar();
        $lists = $datamodel->get_datatables($tglawal, $tglakhir);

            //print_r($lists);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];


                $tombolHapus = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->faktur . "')\"><i class=\"fa fa-trash-alt\"></i></button>";

                $tombolEdit = "<button type=\"button\" class=\"btn btn-sm btn-primary\" onclick=\"edit('" . $list->faktur . "')\"><i class=\"fa fa-pencil-alt\"></i></button>";


                $row[] = $no;
                $row[] = $list->faktur;
                $row[] = $list->tglfaktur;
                $row[] = $list->namapelanggan;

                $db = \Config\Database::connect();
                $jumlahItem = $db->table('detail_barangkeluar')->where('detfaktur', $list->faktur)->countAllResults();
                $row[] ="<span style=\"cursor: pointer; font-weight: bold; color: blue;\" onclick=\"detailItem('".$list->faktur."')\">$jumlahItem</span>";
                

                $row[] =  $tombolHapus . " " . $tombolEdit;
                $row[] = '';
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all($tglawal, $tglakhir),
                "recordsFiltered" => $datamodel->count_filtered($tglawal, $tglakhir),
                "data" => $data
            ];
            echo json_encode($output);
    }
    public function detailItem()
    {
        if ($this->request->isAjax()) {
            $faktur = $this->request->getPost('faktur');
            $modelDetail = new ModelDetailBarangKeluar();

            $data = [
                'tampildatadetail' => $modelDetail->dataDetail($faktur)
            ];

            $json = [
                'data' => view('barangkeluar/modaldetailitem', $data)
            ];
            echo json_encode($json);
        } else {
            exit('Maaf tidak bisa diproses');
        }
    }
    public function input()
    {
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];
        return view('barangkeluar/forminput', $data);
    }

    public function tampilDataTemp()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');

            $modalTempBarangKeluar = new ModelTempBarangKeluar();
            $dataTemp = $modalTempBarangKeluar->tampilDataTemp($nofaktur);
            $data = [
                'tampildata' => $dataTemp
            ];

            $json = [
                'data' => view('barangkeluar/datatemp', $data)
            ];

            echo json_encode($json);
        }
    }

    public function ambilDataBarang()
    {
        if ($this->request->isAJAX()) {
            $kodebarang = $this->request->getPost('kodebarang');

            $modelBarang = new Modelbarang();

            $cekData = $modelBarang->where('brgkode', $kodebarang)->where('isdeleted !=','1')->first();
            if ($cekData == null) {
                $json = [
                    'error' => 'Maaf data barang tidak ditemukan'
                ];
            } else {
                $data = [
                    'namabarang' => $cekData['brgnama']
                ];

                $json = [
                    'sukses' => $data
                ];
            }

            echo json_encode($json);
        }
    }

    public function simpanItem()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $kodebarang = $this->request->getPost('kodebarang');
            $namabarang = $this->request->getPost('namabarang');
            $jml = $this->request->getPost('jml');

            $modelTempBarangKeluar = new ModelTempBarangKeluar();
            $modelBarang = new Modelbarang();

            $ambilDataBarang = $modelBarang->where('brgkode', $kodebarang)->first();
            $totaltempBarang= $modelTempBarangKeluar->cekJumlahByFakturDanKode($nofaktur,$kodebarang);
            $stokBarang = $ambilDataBarang['brgstok']-$totaltempBarang;

            if ($jml > intval($stokBarang)) {
                $json = [
                    'error' => 'Stok tidak mencukupi'
                ];
            } else {
                $modelTempBarangKeluar->insert([
                    'detfaktur' => $nofaktur,
                    'detbrgkode' => $kodebarang,
                    'detjml' => $jml
                ]);

                $json = [
                    'sukses' => 'Item Berhasil ditambahkan'
                ];
            }


            echo json_encode($json);
        }
    }

    public function hapusItem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $modelTempBarangKeluar = new ModelTempBarangKeluar();
            $modelTempBarangKeluar->delete($id);

            $json = [
                'sukses' => 'Item Berhasil dihapus'
            ];

            echo json_encode($json);
        }
    }

    public function modalCariBarang()
    {
        if ($this->request->isAJAX()) {
            $json = [
                'data' => view('barangkeluar/modalcaribarang')
            ];

            echo json_encode($json);
        }
    }

    public function listDataBarang()
    {
        $request = Services::request();
        $datamodel = new Modelbarang();
        
        $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $tombolPilih = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"pilih('" . $list->brgkode . "')\">Pilih</button>";

                $row[] = $no;
                $row[] = $list->brgkode;
                $row[] = $list->brgnama;
                $row[] = number_format($list->brgstok, 0, ",", ".");
                $row[] = $tombolPilih;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];
            echo json_encode($output);
    }

    public function modalPembayaran()
    {
        $nofaktur =  $this->request->getPost('nofaktur');
        $tglfaktur =  $this->request->getPost('tglfaktur');
        $namapelanggan =  $this->request->getPost('namapelanggan');

        $modelTemp =  new ModelTempBarangKeluar();
        $cekData = $modelTemp->tampilDataTemp($nofaktur);

        if ($cekData->getNumRows() > 0) {
            $data = [
                'nofaktur' => $nofaktur,
                'tglfaktur' => $tglfaktur,
                'namapelanggan' => $namapelanggan
            ];

            $json = [
                'data' => view('barangkeluar/modalpembayaran', $data)
            ];
        } else {
            $json = [
                'error' => 'Maaf item belum ada'
            ];
        }

        echo json_encode($json);
    }

    public function simpantransaksi()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $namapelanggan = $this->request->getPost('namapelanggan');
           
            $modelBarangKeluar =  new ModelBarangKeluar();

                $modelBarangKeluar->insert([
                    'faktur' => $nofaktur,
                    'tglfaktur' => $tglfaktur,
                    'namapelanggan' => $namapelanggan,
                    'inputby'=>session()->get('userid')
                ]);
    
                $modelTemp = new ModelTempBarangKeluar();
                $dataTemp = $modelTemp->getWhere(['detfaktur' => $nofaktur]);
    
                $fieldDetail = [];
                // simpan ke tabel barang masuk
                $modelBarang = new Modelbarang();
                foreach ($dataTemp->getResultArray() as $row) {
                    $fieldDetail[] = [
                        'detfaktur' => $row['detfaktur'],
                        'detbrgkode' => $row['detbrgkode'],
                        'detjml' => $row['detjml']
                    ];
                    $data['isdeleted']=0;
                    $data = $modelBarang->get_by_kode($row['detbrgkode']);
                    $data['brgstok']=$data['brgstok']-$row['detjml'];

                    $modelBarang->update($data['brgid'], $data);
                }
    
                $modelDetail = new ModelDetailBarangKeluar();
                $modelDetail->insertBatch($fieldDetail);
    
                $modelTemp->hapusData($nofaktur);
    
                $json = [
                    'sukses' => 'Transaksi berhasil disimpan'
                ];
            

            echo json_encode($json);
        }
    }

    
    

    public function cetakfaktur($faktur)
    {
        $modelBarangKeluar = new ModelBarangKeluar();
        $modelDetail = new ModelDetailBarangKeluar();
        
        $cekData = $modelBarangKeluar->find($faktur);


        if ($cekData != null) {
            $data = [
                'faktur' => $faktur,
                'tanggal' => $cekData['tglfaktur'],
                'namapelanggan' => $cekData['namapelanggan'],
                'detailbarang' => $modelDetail->tampilDataTemp($faktur),
            ];

            return view('barangkeluar/cetakfaktur', $data);
        } else {
            return redirect()->to(site_url('barangkeluar/input'));
        }
    }

    public function hapusTransaksi()
    {
        if ($this->request->isAJAX()) {
            // // Set your Merchant Server Key
            // \Midtrans\Config::$serverKey = 'SB-Mid-server-K5H0XsWr8DnRAjxqvlbqrtE-';
            // // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            // \Midtrans\Config::$isProduction = false;
            // // Set sanitization on (default)
            // \Midtrans\Config::$isSanitized = true;
            // // Set 3DS transaction for credit card to true
            // \Midtrans\Config::$is3ds = true;

            $faktur = $this->request->getPost('faktur');

            $modelBarangKeluar = new ModelBarangKeluar();
            $dataBarangKeluar = $modelBarangKeluar->find($faktur);
            // hapus detail
            $db = \Config\Database::connect();

            // \Midtrans\Transaction::cancel($dataBarangKeluar['order_id']);
            //get detail old brg masuk
            $modelBarang=new Modelbarang();
            $dataDetOld=$db->table('detail_barangkeluar')->where('detfaktur',$faktur)->get()->getResult();

            foreach($dataDetOld as $dt){
                
                $dataBarang=$modelBarang->where('brgkode',$dt->detbrgkode)->first();
                $dataBarang['brgstok']=$dataBarang['brgstok']+$dt->detjml;
                $data['isdeleted']=0;
                $modelBarang->update($dataBarang['brgid'], $dataBarang);
            }
            $db->table('detail_barangkeluar')->delete(['detfaktur' => $faktur]);
            $modelBarangKeluar->delete($faktur);

            $json = [
                'sukses' => 'Transaksi berhasil dihapus'
            ];

            echo json_encode($json);
        }
    }

    public function edit($faktur)
    {
        $modelBarangKeluar = new ModelBarangKeluar();
        $rowData = $modelBarangKeluar->find($faktur);

        $data = [
            'nofaktur' => $faktur,
            'tanggal' => $rowData['tglfaktur'],
            'namapelanggan' => $rowData['namapelanggan']
        ];

        return view('barangkeluar/formedit', $data);
    }

    

    public function tampilDataDetail()
    {

        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');

            $modelDetail = new ModelDetailBarangKeluar();
            $dataTemp = $modelDetail->tampilDataTemp($nofaktur);
            $data = [
                'tampildata' => $dataTemp
            ];

            $json = [
                'data' => view('barangkeluar/datadetail', $data)
            ];

            echo json_encode($json);
        }
    }

    public function hapusItemDetail()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarangKeluar = new ModelBarangKeluar();

            $rowData = $modelDetail->find($id);
            $noFaktur = $rowData['detfaktur'];
            $modelBarang = new Modelbarang();
            $data = $modelBarang->get_by_kode($rowData['detbrgkode']);
            $data['brgstok']=($data['brgstok']+$rowData['detjml']);
            $data['isdeleted']=0;
            $modelBarang->update($data['brgid'], $data);
            $modelDetail->delete($id);


            $json = [
                'sukses' => 'Item Berhasil dihapus'
            ];

            echo json_encode($json);
        }
    }

    public function editItem()
    {
        if ($this->request->isAJAX()) {
            $iddetail = $this->request->getPost('iddetail');
            $jml = $this->request->getPost('jml');

            $modelDetail = new ModelDetailBarangKeluar();
            $modelBarangKeluar = new ModelBarangKeluar();



            $rowData = $modelDetail->find($iddetail);
            //update stock barang 
            $modelBarang = new Modelbarang();
            $data = $modelBarang->get_by_kode($rowData['detbrgkode']);
            if((($data['brgstok']+$rowData['detjml'])-$jml)<0){
                $json = [
                    'gagal' => 'Item tidak Berhasil di update, stok tidak cukup'
                ];
            }else{
                $data['brgstok']=($data['brgstok']+$rowData['detjml'])-$jml;
                $data['isdeleted']=0;
                $update =$modelBarang->update($data['brgid'], $data);
    
                $noFaktur = $rowData['detfaktur'];
                
    
                // update pada data tabel detail
    
                $modelDetail->update($iddetail, [
                    'detjml' => $jml,
                ]);
    
                
    
                $json = [
                    'sukses' => 'Item Berhasil di update'
                ];
               
            }
            
            

            echo json_encode($json);
        }
    }

    public function simpanItemDetail()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getVar('nofaktur');
            $kodebarang = $this->request->getVar('kodebarang');
            $namabarang = $this->request->getVar('namabarang');
            $jml = $this->request->getVar('jml');
           
            $modelTempBarangKeluar = new ModelDetailBarangKeluar();
            $modelBarang = new Modelbarang();

            $ambilDataBarang = $modelBarang->where('brgkode', $kodebarang)->first();

            $stokBarang = $ambilDataBarang['brgstok'];

            if ($jml > intval($stokBarang)) {
                $json = [
                    'error' => 'Stok tidak mencukupi'
                ];
            } else {
                $modelTempBarangKeluar->insert([
                    'detfaktur' => $nofaktur,
                    'detbrgkode' => $kodebarang,
                    'detjml' => $jml
                ]);

                //update stock barang 
                $modelBarang = new Modelbarang();
                $data = $modelBarang->get_by_kode($kodebarang);
                $data['brgstok']=$data['brgstok']-$jml;
                $data['isdeleted']=0;
                $update =$modelBarang->update($data['brgid'], $data);
                $modelBarangKeluar = new ModelBarangKeluar();

                
                $json = [
                    'sukses' => 'Item Berhasil ditambahkan'
                ];
            }


            echo json_encode($json);
        }
    }
}
