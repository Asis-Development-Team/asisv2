<html>
    <title>Cetak Nota Penjualan</title>
    <style>
        #tss{
            font-size: 12px;
            font-family:Courier New;
        }
        #tss tr td{
            font-size: 12px;     
            font-family:Courier New;
        }
        .bdr_btm{
            border-bottom: 1px dashed #000;
            font-size: 12px;
            font-family:Courier New;
        }
        @media print{
            #man {
                visibility:hidden;
            }
        }
    </style>
    <body style="font-size: 11px">
                <table width='100%' id="tss" >
            <tr>
                <td colspan="3" width='60%' class="bdr_btm" valign="top">   
                                  
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
                <td class="bdr_btm" align='right'>                    
                    <table width="100%" >
                        <tr>
                            <td colspan="4">
                                <b>NOTA PENJUALAN</b> 
                            </td>
                        </tr>
                        <tr>
                            <td width="35%">ID Penjualan</td>
                            <td>: <?php print $result['invoice_no_order'] ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal </td>
                            <td>: <?php print $this->tools->tanggal_indonesia($result['invoice_tanggal_faktur']); ?></td>
                        </tr>
                        <tr>
                            <td>Pembayaran </td>
                            <td>: 
                            <?php 
                            print $pembayaran = ($result['invoice_status_pembayaran'] == 'Tunai') ? 'Tunai' : 'Tempo' . $result['invoice_hari_jatuh_tempo']; 
                            ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="bdr_btm">
                    <table width='100%'>
                        <tr>
                            <td width='15%'>Nama Pelanggan</td>
                            <td width='35%'>: <?php print $result['pelanggan_nama'] ?></td>
                            <td width='15%'></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td rowspan="2" valign="top">: <?php print $result['pelanggan_alamat'] ?></td>
                            <td valign="top"></td>
                            <td rowspan="2" valign="top"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No Telp</td>
                            <td>: <?php print $result['pelanggan_telepon'] ?></td>
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
                            <td>Deskripsi Barang</td>
                            <td width='10%'>QTY</td>
                            <td width='10%' align='right'>Harga</td>
                            <td width='10%' align='right'>Jumlah</td>
                        </tr>
                            
                            <?php 
                            $no     =   '1';
                            $total  =   '';

                            foreach($detail as $detail): 
                            ?>
                            <tr>
                                <td><?php print $no; ?></td>
                                <td><?php print $detail['invoice_detail_nama_produk'] ?></td>
                                <td><?php print $detail['invoice_detail_jumlah_produk'] ?></td>
                                <td align='right'><?php print $this->tools->format_angka($detail['invoice_detail_harga'],2) ?></td>
                                <td align='right'><?php print $this->tools->format_angka($detail['invoice_detail_total'],2); ?></td>
                            </tr>
                        <?php 
                            $no++;
                            $total  +=  $detail['invoice_detail_total'];                        
                        endforeach; 
                        ?>
                                                
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width='100%' >
                        <tr>
                            <td style='border : 1px dotted #000000; padding:10px' width='60%'>
							<!--
                                Barang telah di terima dalam kondisi baik dan cukup. <br>
                                Nota ini sekaligus sebagai kartu garansi. Garansi tidak berlaku bila nota hilang/rusak.<br>
                                <b>BCA 0130935010 a.n. PT. ASTON PRINTER INDONESIA</b>
								-->
                            </td>
                            <td align="right" valign="top">
                                <table width="100%" >
                                    <td width='40%' align='right'><b>Total</b></td>
                                    <td width='60%' align='right'><b><?php print $this->tools->format_angka($total,2); ?></b></td>
                                                                    </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" align='center'>
                    <table width='90%' align='center'>
                        <tr>
                            <td width='33.3%' align='center'>
                                Diterima Oleh<br/>
                                <br/><br/><br/><br/>
                                <?php print $result['pelanggan_nama'] ?>                            </td>
                            <td width='33.3%' align='center'>
                            </td>
                            <td width='33.3%' align='center'>
                                Di Buat Oleh<br/>
                                <br/><br/><br/><br/>
                                
                                <?php print $result['nama_sales'] ?>                            

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
            <button type=button onclick='window.print();'>Cetak Nota Penjualan</button>
        </div>

    </body>

    <script src="/assets/admin/global/plugins/jquery.min.js" type="text/javascript"></script>
    
    <script>
        window.onload = window.print();    
    </script>    
</html>