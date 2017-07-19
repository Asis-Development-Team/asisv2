<html>
    <title>Cetak Pembayaran Piutang</title>
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


                                <table width="50%" align="center">
                    <tbody><tr>
                        <td style="font-size: 18px; font-weight: bold" align="center">
                            PT. ASTON SISTEM INDONESIA                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; font-weight: bold" align="center">
                            Jl. Imam Bonjol No. 28 Salatiga,                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <h5>Bukti Pembayaran Piutang Usaha</h5>
                        </td>
                    </tr>
                </tbody></table> 


            
                <div style="clear: both"></div>
                <hr style="border:1px solid #000000">

                                    
                <table width='100%' id="tss" >
            <tr>
                <td colspan="3" width='60%' class="bdr_btm" valign="top">   
                                  
                    <table width="100%">
                        <tr>
                            <td width="14%">
                                <b>No Referensi</b>
                            </td>
                            <td width="86%">: <?php print $result['pembayaran_kode'] ?></td>
                        </tr>
                        <tr>
                            <td>
                                <b>Tanggal</b>
                            </td>
                            <td>: <?php print $this->tools->tanggal_indonesia($result['pembayaran_tanggal_faktur']) ?></td>
                        </tr>

                    </table>
                </td>
                <td class="bdr_btm" align='right'>                    
                    <table width="100%" >
                        <tr>
                            <td width="35%">Dari</td>
                            <td>: <?php print $result['pelanggan_nama'] ?></td>
                        </tr>
                        <tr>
                            <td>Keterangan </td>
                            <td>: <?php print $result['pembayaran_keterangan']; ?></td>
                        </tr>

                    </table>
                    <br /><br />
                </td>
            </tr>
            <?php /*
            <tr>
                <td colspan="4" class="bdr_btm">
                    <table width='100%'>
                        <tr>
                            <td width='15%'>Nama Pelanggan</td>
                            <td width='35%'>: <?php //print $result['pelanggan_nama'] ?></td>
                            <td width='15%'></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td rowspan="2" valign="top">: <?php //print $result['pelanggan_alamat'] ?></td>
                            <td valign="top"></td>
                            <td rowspan="2" valign="top"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No Telp</td>
                            <td>: <?php //print $result['pelanggan_telepon'] ?></td>
                            <td valign="top"></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            */ ?>
            <tr>
                <td colspan="4" class="bdr_btm">
                    <table width='100%'>
                        <tr>
                            <td width='3%'>No</td>
                            <td>No Invoice</td>
                            <td width=''>Tanggal Transaksi</td>
                            <td width='' align='right'>Saldo</td>
                            <td width='' align='right'>Pembayaran</td>
                        </tr>
                            

                        <tr>
                            <td>1</td>
                            <td><?php print $result['pembayaran_no_invoice'] ?></td>
                            <td><?php print $this->tools->tanggal_indonesia($result['pembayaran_tanggal_faktur']) ?></td>
                            <td align='right'><?php print $this->tools->format_angka($piutang['pembayaran_detail_saldo'],2) ?></td>
                            <td align='right'><?php print $this->tools->format_angka($result['pembayaran_total'],2); ?></td>
                        </tr>

                                            
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width='100%' >
                        <tr>
                            <td style='border : 0px dotted #000000; padding:10px' width='60%'>
							<!--
                                Barang telah di terima dalam kondisi baik dan cukup. <br>
                                Nota ini sekaligus sebagai kartu garansi. Garansi tidak berlaku bila nota hilang/rusak.<br>
                                <b>BCA 0130935010 a.n. PT. ASTON PRINTER INDONESIA</b>
								-->
                                &nbsp;
                            </td>
                            <td align="right" valign="top">
                                <table width="100%" >
                                    <td width='40%' align='right'><b>Total</b></td>
                                    <td width='60%' align='right'><b><?php print $this->tools->format_angka($result['pembayaran_total'],2); ?></b></td>
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
                                Diperiksan Oleh<br/>
                                <br/><br/><br/><br/>
                                <?php //print $result['pelanggan_nama'] ?>                            
                                (.......................)
                                </td>
                            <td width='33.3%' align='center'>
                            </td>
                            <td width='33.3%' align='center'>
                                Disetujui Oleh<br/>
                                <br/><br/><br/><br/>
                                
                                <?php //print $result['nama_sales'] ?>                            
                                (.......................)
                                </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <?php /*
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
        */ ?>

    </body>

    <script src="/assets/admin/global/plugins/jquery.min.js" type="text/javascript"></script>
    
    <script>
        window.onload = window.print();    
    </script>    
</html>