<html>
    <title>Cetak Purchase Order</title>
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
                <td colspan="3" width='75%' class="">   
                                  
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
                <td class="">                    
                    <table width="100%">
                        <tr>
                            <td colspan="4">
                                <b>PURCHASE ORDER</b> 
                            </td>
                        </tr>
                        <tr>
                            <td width="30%">Nomor Bukti</td>
                            <td>: <?php print $result['penawaran_nomer'] ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal </td>
                            <td>: <?php print $this->tools->tanggal_indonesia($result['penawaran_tanggal_pesan']); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="bdr_btm">&nbsp;
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
                        </tr>

                            <?php 
                            $no     =   '1';
                            $total  =   '';

                            foreach($detail as $detail):
                            ?>
                            <tr>
                                <td><?php print $no; ?></td>
                                <td><?php print $detail['penawaran_detail_product_kode'] ?></td>
                                <td><?php print $detail['penawaran_detail_product_nama'] ?></td>
                                <td><?php print $detail['penawaran_detail_jumlah'] ?></td>
                            </tr>
                            <?php 
                                $no++;

                                $total  +=  $detail['penawaran_detail_jumlah'];

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
                            <td></td>
                            <td width='10%' align='right'><b>Total</b></td>
                            <td width='10%'><b><?php print $total ?></b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" align='center'>
                    <table width='90%' align='center'>
                        <tr>
                            <td width='33.3%' align='center'>
                                
                                Dibuat Oleh<br/><br/><br/><br/>
                                (<?php print $result['nama_user'] ?>)     

                            </td>
                            <td width='33.3%' align='center'>
                            </td>
                            <td width='33.3%' align='center'>
                                Disetujui,<br/><br/><br/><br/>
                                (...................)
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
            <button type=button onclick='window.print();'>Cetak TTS</button>
        </div>

    <script src="/assets/admin/global/plugins/jquery.min.js" type="text/javascript"></script>
    
    <script>

        window.onload = window.print();     
    
    </script>
    
    </body>


</html>