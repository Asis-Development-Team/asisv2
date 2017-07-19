                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/admin/dashboard">Dashboard</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active"><?php print $page_title ?></span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->


                    <!-- BEGIN PAGE HEAD-->
                    <div class="page-head">
                        <!-- BEGIN PAGE TITLE -->

                        
	                        <div class="page-title">
	                            <h1><?php print $page_title ?></h1>
	                        </div>
                        
                        <!-- END PAGE TITLE -->           

                    </div>
                    <!-- END PAGE HEAD-->

                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">
                        <div class="col-md-12">

                            <!-- BEGIN SAMPLE TABLE PORTLET-->
                            <div class="portlet box purplex">

                                <div class="portlet-body">
                                    <div class="table-scrollable">
                                        <table class="table table-striped table-bordered table-advance table-hover">
                                            <thead>
                                                <tr>
                                                    
                                                    <th class="bold" width="90%">Title</th>
                                                	<th class="bold" width="10%"></th>
                                                    
                                                	
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>

                                            	<tr style="background: #f2f2f2">
                                            		<td colspan="2"><h4 class="text-center">Generate From astonpri_asisall</h4></td>
                                            	</tr>
                                            	
                                              	<tr class="">
                                                	<td>Generate User Level</td>
                                                	<td class="text-center"> <button class="btn btn-primary btn-generate" id="btn-generate-user-level">Execute</button> </td>                                              
                                                </tr>

                                                <tr class="">
                                                	<td>Generate Menu</td>
                                                	<td class="text-center"> <button class="btn btn-primary btn-generate" id="btn-generate-menu">Execute</button> </td>                                              
                                                </tr>
                                                

                                                <tr class="">
                                                	<td>Generate Branch</td>
                                                	<td class="text-center"> <button class="btn btn-primary btn-generate" id="btn-generate-branch">Execute</button> </td>                                              
                                                </tr>

                                                <tr class="">
                                                	<td>Generate User Data</td>
                                                	<td class="text-center"> <button class="btn btn-primary btn-generate" id="btn-generate-user">Execute</button> </td>                                              
                                                </tr>


                                                <tr style="background: #f2f2f2">
                                            		<td colspan="2" class="text-center">



                                                        <div id="dragable" class="pull-right" style="border: 1px solid #c2c2c2; width:300px;">
                                            			Select Database<br />
                                            			<select name="dbname" class="dbname" id="dbname" class="form-controlx" style="padding: 5px">
                                            				<option></option>
                                            				<?php 
                                            				
                                            				$dblist 	=	$this->utility->list_database();

                                            				foreach($dblist as $dbname):
                                            					
                                            					//if (preg_match("/\bastonpri\b/i", $dbname)):
                                            					
                                            						print '<option value="'.$dbname.'">'.$dbname.'</option>';
                                            					
                                            					//endif;

                                            				endforeach;
                                            				?>
                                            			</select>
                                                        </div>

                                            		</td>
                                            	</tr>




                                            	<tr class="">
                                                	<td>
                                                        Generate Table <strong>'pesan'</strong> 
                                                        <small> (ada table pesan disetiap outlet, dump dari semua outlet)</small>
                                                    </td>
                                                	<td class="text-center"> 
                                                		<button class="btn btn-primary btn-generate" id="btn-dump-pesan">Execute</button> 
                                                	</td>                                              
                                                </tr>

                                                <tr class="hidden">
                                                    <td>
                                                        Generate Table <strong>'klasifikasi_akun'</strong>
                                                        <small> (semua outlet sama, cukup ambil dari satu outlet / dari pusat)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate" id="btn-dump-klasifikasi-akun">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'rekening'</strong>
                                                        <small> (ambil dari semua outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate" id="btn-dump-rekening">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'labarugi_marketing'</strong>
                                                        <small> (semua outlet sama, cukup ambil dari satu outlet / dari pusat)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate" id="btn-dump-laba-rugi-marketing">Execute</button> 
                                                    </td>                                              
                                                </tr>


                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'customer'</strong>
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate" id="btn-dump-customer">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'supplier'</strong>
                                                        <small style="color: #000"> (semua outlet sama, cukup ambil dari satu outlet / dari pusat)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate" id="btn-dump-supplier">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'employee'</strong>
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_employee" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>


                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'rekening'</strong>
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_rekening" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'klasifikasi_akun' - 'sub_klasifikasi_akun'</strong>
                                                        <small style="color: #ff0000"> (ambil dari satu outlet saja)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_klasifikasi_akun" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>


                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'produk' (menu data > data produk)</strong>
                                                        <small style="color: #ff0000"> (ambil dari satu outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_product" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'kelompok_produk' (relasi dari menu data > data produk)</strong>
                                                        <small style="color: #ff0000"> (ambil dari satu outlet saja)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_kelompok_produk" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'merk_produk' (relasi dari menu data > data produk)</strong>
                                                        <small style="color: #ff0000"> (ambil dari satu outlet saja)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_merk_produk" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>


                                                <tr class="">
                                                    <td>
                                                        Generate Table <strong>'bank' (dari menu data > data bank)</strong>
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_bank" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>


                                                <tr class="">
                                                    <td>
                                                        Generate Stock Product
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_stock_product" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Data Kode Dari Table 'perusahaan'
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet atau cukup dari pusat)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_perusahaan" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>


                                                <tr class="">
                                                    <td class="text-center" colspan="2"> 
                                                        <h3>Generate Menu Pembelian</h3>
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Pembelian > PO Outlet
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_po_outlet" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="hidden">
                                                    <td>
                                                        Generate Pembelian > Data Pembayaran Hutang
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_pembayaran_hutang" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td class="text-center" colspan="2"> 
                                                        <h3>Generate Menu Penjualan</h3>
                                                    </td>                                              
                                                </tr>

                                                <tr class="">
                                                    <td>
                                                        Generate Penjualan
                                                        <small style="color: #ff0000"> (ambil dari setiap outlet)</small>
                                                    </td>
                                                    <td class="text-center"> 
                                                        <button class="btn btn-primary btn-generate-new" id="dump_penjualan" data-rel="dump-content">Execute</button> 
                                                    </td>                                              
                                                </tr>



                                            </tbody>
                                        </table>

                                    </div>



                                </div>
                            </div>
                            <!-- END SAMPLE TABLE PORTLET-->


                        </div>
                    </div>


                    

                    <!-- END PAGE BASE CONTENT -->
