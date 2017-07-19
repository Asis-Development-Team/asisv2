<html>
    <title>Cetak Bukti Pembayaran Hutang</title>
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
                            <td colspan="4" class="bdr_btm">
                                <b>BUKTI PEMBAYARAN HUTANG</b> 
                            </td>
                        </tr>
                        <tr>
                            <td width="35%">ID</td>
                            <td>: <?php print $result['pembayaran_kode'] ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal </td>
                            <td>: <?php print $this->tools->tanggal_indonesia($result['pembayaran_tanggal_faktur']) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="bdr_btm">
                    <table width='100%'>
                        <tr>
                            <td width='15%'>Supllier</td>
                            <td width='35%'>: <?php print $result['supplier_nama'] ?></td>
                            <td width='15%'></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width='15%'></td>
                            <td width='35%'>: <?php print $result['supplier_alamat'] ?></td>
                            <td width='15%'></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width='15%'></td>
                            <td width='35%'>&nbsp; <?php print $result['supplier_nama_kota'] ?></td>
                            <td width='15%'></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="bdr_btm">
                    <table width="100%">
                        <tr>
                            <td width="5%" align="center">No</td>
                            <td width="25%">No Invoice </td>
                            <td width="50%">Tanggal Invoice</td>
                            <td align="right">Saldo</td>
                            <td align="right">Di Bayar</td>
                        </tr>
                        
                        <?php 
                        $no 	= '1';
                        $total 	= '';

                        foreach($detail as $detail):
                        ?>
                        <tr style="height: 25px;">
                                <td align="center"><?php print $no; ?></td>
                                <td><?php print $detail['pembayaran_detail_no_invoice'] ?></td>
                                <td><?php print @$this->tools->tanggal_indonesia($detail['pembayaran_detail_tanggal_invoice']); ?></td>
                                <td align="right">
                                    <div style="float: left"></div>
                                    <div style="float: right"><?php print $this->tools->format_angka($detail['pembayaran_detail_saldo'],2) ?></div>
                                </td>
                                <td align="right">
                                    <div style="float: left"></div>
                                    <div style="float: right"><?php print $this->tools->format_angka($detail['pembayaran_detail_bayar'],2) ?></div></td>
                            </tr>

                        <?php 
                        	$total 	+=	$detail['pembayaran_detail_bayar'];
                        	$no++;
                        endforeach; 
                        ?>
                                                </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width="100%">
                        <tr>
                            <td colspan="3" align="right" width="80%"><b>Biaya Administrasi bank</b></td>
                            <td align="right"><b>0.00</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width="100%">
                        <tr>
                            <td colspan="3" align="right" width="80%"><b>Total</b></td>
                            <td align="right"><b><?php print $this->tools->format_angka($total,2) ?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" align='center'>
                    <table width='90%' align='center'>
                        <tr>
                            <td width="33%" align="center">Di buat oleh, <br/><br/><br/><br/><br/><?php print $this->session->sess_surname ?></td>
                            <td width="33%" align="center">Mengetahui, <br/><br/><br/><br/><br/>(.....................)</td>                                                        
                            <td width="33%" align="center">Diterima oleh, <br/><br/><br/><br/><br/><?php print $result['supplier_nama'] ?></td>
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

    </body>

    <script src="/assets/admin/global/plugins/jquery.min.js" type="text/javascript"></script>
    
    <script>

        window.onload = window.print();     
    
    </script>    
</html>