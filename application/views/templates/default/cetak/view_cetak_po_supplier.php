<html>
    <title>Print PO Supplier</title>
    <style>
        #tss{
            font-size: 10px;
            font-family:Courier New;
        }
        #tss tr td{
            font-size: 10px;     
            font-family:Courier New;
        }
        .bdr_btm{
            border-bottom: 1px dashed #000;
            font-size: 10px;
            font-family:Courier New;
        }
        @media print{
            #man {
                visibility:hidden;
            }
        }
    </style>
    <body style="font-size: 11px">
                <table width='100%' id="tss">
            <tr>
                <td colspan="3" width='75%' class="bdr_btm" valign="top">   
                                  
                    <table width="100%">
                        <tr>
                            <td>
                                <b>PT. ASTON SISTEM INDONESIA</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>JL. IMAM BONJOL NO. 28 SALATIGA</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b></b>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="bdr_btm">                    
                    <table width="100%">
                        <tr>
                            <td colspan="4">
                                <b>NOTA PEMBELIAN</b> 
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">ID Pembelian</td>
                            <td>: <?php print $result['po_nomer_po'] ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal </td>
                            <td>: <?php print $this->tools->tanggal_indonesia($result['po_tgl_input']); ?></td>
                        </tr>
                        <tr>
                            <td>Pembayaran </td>
                            <td>: <?php print $result['po_cara_bayar'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="bdr_btm">
                    <table width='100%'>
                        <tr>
                            <td width='15%'>Nama Suplier</td>
                            <td width='35%'>: <?php print $result['supplier_nama'] ?></td>
                            <td width='15%'></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td rowspan="2" valign="top">: <?php print $result['supplier_alamat'] ?></td>
                            <td valign="top"></td>
                            <td rowspan="2" valign="top"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No Telp</td>
                            <td>: <?php print $telp = ($result['supplier_telepon'] == '0' || $result['supplier_telepon'] == '') ? '-' : $result['supplier_telepon'] ?></td>
                            <td valign="top"></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="bdr_btm">
                    <table width='100%'>
                        <tr>
                            <td width='3%'>No</td>
                            <td width='10%'>Kode</td>
                            <td>Deskripsi Barang</td>
                            <td width='10%'>QTY</td>
                            <td width='10%' align='right'>Harga</td>
                            <td width='10%' align='right'>Jumlah</td>
                        </tr>
                        
                        <?php 
                        $no     = 1;
                        $total  = '';

                        foreach($detail as $detail):
                        ?>
                        <tr>
                            <td><?php print $no; ?></td>
                            <td><?php print $detail['po_detail_product_kode'] ?></td>
                            <td><?php print $detail['detail_nama_produk'] ?></td>
                            <td><?php print $detail['po_detail_jumlah_permintaan'] ?></td>
                            <td align='right'><?php print $this->tools->format_angka($detail['po_detail_harga'],0)  ?></td>
                            <td align='right'><?php print $this->tools->format_angka($detail['po_detail_total'],0)  ?></td>
                        </tr>
                        <?php 
                            $no++;

                            $total  += $detail['po_detail_total'];   
                        endforeach;
                        ?>

                        </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width='100%'>
                        <tr>
                            <td width='3%'></td>
                            <td width='10%'></td>
                            <td></td>
                            <td width='10%'></td>
                            <td width='10%' align='right'><b>Total</b></td>
                            <td width='10%' align='right'><b><?php print $this->tools->format_angka($total,0) ?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" align='center'>
                    <table width='90%' align='center'>
                        <tr>
                            <td width='33.3%' align='center'>                                
                                Di Buat Oleh,
                                <br/><br/><br/><br/><br/>
                                <?php print ucwords($this->session->sess_surname) ?>                            
                                </td>
                            <td width='33.3%' align='center'>
                            </td>
                            <td width='33.3%' align='center'>
                                Disetujui,
                                <br/><br/><br/><br/><br/>
                                (.................)
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div id=man style="border:1px solid red;background-color:silver;padding:5px;width:80%;">
            Pengaturan Printer Epson LX 310: <hr>
            <ul>
                <li>Ukuran kertas 21 cm * 14.8 cm / A4 tanpa header dan footer</li>
                <li>DPI 120 * 144</li>
                <li>Cetak menggunakan browser Mozilla</li>
            </ul>
            <hr>
            <button type=button onclick='window.print();'>Cetak</button>
        </div>

    <script src="/assets/admin/global/plugins/jquery.min.js" type="text/javascript"></script>
    
    <script>

        window.onload = window.print();     
    
    </script>
    
    </body>

    </body>
</html>