                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        <h1 class="hidden">Order</h1>

                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li class="active">Data Order</li>
                        </ol>

                        <div class="pull-right">
 
                                
                            <form name="search-product" id="form-search-product" method="get">
                                            
                                <div style="float:left; margin-right:4px;"><input type="text" class="form-control" placeholder="Search" name="q" value="<?php print @$_GET['q'] ?>" /></div>
                                <div style="float:left; margin-right:4px;">


                                </div>
                                <div style="float:left; margin-right:0px;">
                                    <button type="submit" class="btn green tooltips" data-container="body" data-placement="top" data-original-title="Search"><i class="fa fa-search"></i></button>
                                    <?php /*
                                    <button id="export-product-to-excel" class="btn green tooltips" data-container="body" data-placement="bottom" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></button>
                                    */ ?>
                                    <button type="button" class="btn green tooltips btn-refresh" data-container="body" data-placement="top" data-original-title="Reset"><i class="fa fa-refresh"></i></button>                                                
                                    <a href="/admin/data/product/export" id="export-product-to-excel" class="btn green tooltips hidden" data-container="body" data-placement="top" data-original-title="Export To Excel"><i class="fa fa-file-excel-o"></i></a>
                                    
                                </div>
   

                            </form>
                        </div>

                    </div>
                    <!-- END BREADCRUMBS -->                    


                    <!-- BEGIN SIDEBAR CONTENT LAYOUT -->
                    <div class="page-content-container">
                        <div class="page-content-row">
                            
                           <?php /* page side bar goes here */ ?>

                            <div class="page-content-col">


                                <div class="row">

                                    <div class="col-md-12">
                                        <!-- BEGIN SAMPLE TABLE PORTLET-->
                                        <div class="portlet">
                                            <div class="portlet-title">
                                                

                                                <div class="caption">
                                                    <i class="fa fa-databasex"></i>Data Order (<span class="total-entry"><?php print $total; ?></span>) 
                                                </div>

                                                <div class="toolsx pull-right hidden">
                                                    <a href="/admin/<?php print $this->uri->segment('2') ?>/<?php print $this->uri->segment('3') ?>/<?php print $this->uri->segment('4') ?>/add" class="btn green"><i class="fa fa-edit"></i> Add Data</a>
                                                </div>

                                            </div>
                                            <div class="portlet-body">






                                                <div class="table-scrollable">
                                                    <table class="table table-striped table-bordered table-advance table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th width="2%" class="hidden"> </th>
                                                                
                                                                <th class="text-center">
                                                                    <i class=""></i> User</th>

                                                                <th class="text-center">
                                                                    <i class=""></i> Invoice Number / Order Number</th>
                                                                
                                                                <th class="hidden-xs text-center">
                                                                    <i class=""></i> Date 
                                                                </th>

                                                                <th class="hidden-xs text-center">
                                                                    <i class=""></i> Qty 
                                                                </th>

                                                                <th class="hidden-xs text-center">
                                                                    <i class=""></i> Total (IDR)
                                                                </th>


                                                                <th class="hidden-xs text-center">
                                                                    <i class=""></i> Payment Status 
                                                                </th>


                                                                <th> </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php 
                                                            foreach($result as $result):

                                                                $buyer      =   $this->data->user_single('id', $result['user_id']);

                                                                $request    =   $this->data->data_order_detail($result['order_id']);
                                                                $total      =   $request['total'];
                                                                $detail     =   $request['result'];

                                                            ?>
                                                            <tr class="parent-<?=$result['order_id']?> counter">


                                                                <td class="center hidden">
                                                                    <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result['order_id'] ?>" id="checkbox-delete-<?php print $result['order_id'] ?>" name="multichecked[]" />
                                                                </td>

                                                                <td>
                                                                    <?php print $buyer['result']['surname'] . '<br />(' . $buyer['result']['email'].')'; ?>
                                                                </td>

                                                         
                                                                <td>
                                                                    <?php print stripslashes($result['order_invoice_number']) . ' [' . $result['order_number'] . ']'?>
                                                                    
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php print nice_date($result['order_date'],'d M Y'); ?>
                                                                </td>

                                                                <td class="text-center">
                                                                    <?php print $total; ?>
                                                                </td>

                                                                <td class="text-center">
                                                                    <?php print $this->tools->format_angka($result['order_total_invoice'], 0) ?>
                                                                </td>

                                                                <td class="text-center">
                                                                    <div class="col-sm-8">
                                                                    <?php print ucfirst($result['order_payment_status']); ?>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <a data-target="#full-payment-<?php print $result['order_ids'] ?>" data-toggle="modal" data-container="body" data-placement="top" data-original-title="View Payment" class="tooltips">
                                                                        <i class="fa fa-eye"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
    

                                                                <td>

                                                                    <div class="text-center">
                                                                    <a data-target="#full-width-<?php print $result['order_ids'] ?>" data-toggle="modal" data-container="body" data-placement="top" data-original-title="View Detail" class="tooltips"><i class="fa fa-eye"></i></a>


                                                                    <a href="/admin/data/events/edit/?id=<?php //print $result['event_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips hidden"><i class="fa fa-edit"></i></a>
                                                                    <a href="javascript:;" class="delete-single tooltips" data-type="<?php print $page_identifier ?>" data-container="body" data-placement="top" data-original-title="Delete" data-id="<?php print $result['order_id'] ?>" id="delete-product-<?php print $result['order_id'] ?>"><i class="fa  fa-trash-o"></i></a>
                                                                    </div>                                                                    

                                                                </td>


                                                            </tr>

                                                                <tr class="modal" id="">
                                                                    <td colspan="7">

                                                                        <div id="full-payment-<?php print $result['order_ids'] ?>" class="modal container fade" tabindex="-1">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                <h4 class="modal-title">Payment Detail Invoice Number: <?php print $result['order_invoice_number'] ?></h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                
                                                                                <?php if($result['order_payment_status'] == 'pending'): ?>
                                                                                    <h4>Payment Status: <?php print $result['order_payment_status'] ?></h4>
                                                                                <?php else: ?>

                                                                                    <div class="row">
                                                                                        <div class="col-sm-2">
                                                                                            Sender Name:<br />
                                                                                            Transfer Date: <br />
                                                                                            Payment Method:<br />
                                                                                            Amount:
                                                                                        </div>

                                                                                        <div class="col-sm-8">
                                                                                            <?php print $result['order_payment_sender_name'] . '<br />'; ?>
                                                                                            <?php print nice_date($result['order_payment_date'], 'd M Y') . '<br />'; ?>
                                                                                            <?php print $result['order_payment_method'] . '<br />'; ?>
                                                                                            <?php print $this->tools->format_angka($result['order_payment_total'],0); ?>
                                                                                        </div>
                                                                                    </div>

                                                                                <?php endif; ?>
                                                                                
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" data-dismiss="modal" class="btn btn-outline dark">Close</button>
                                                                                <button type="button" class="btn green hidden">Save changes</button>
                                                                            </div>
                                                                        </div>

                                                                    </td>
                                                                </tr>

                                                                <tr class="modal" id="detail-order-<?php print $result['order_ids'] ?>">
                                                                    <td colspan="7">
                                                                        <div id="full-width-<?php print $result['order_ids'] ?>" class="modal container fadexx" tabindex="-1">


                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                <h4 class="modal-title">Order Detail Invoice Number: <?php print $result['order_invoice_number'] ?></h4>
                                                                            </div>

                                                                            <div class="modal-header">
                                                                                
                                                                                <div class="col-sm-6">
                                                                                    Buyer:<br />
                                                                                    <?php 
                                                                                    print $buyer['result']['surname'].'<br />';
                                                                                    if($buyer['result']['user_address']):
                                                                                    print $buyer['result']['user_address'] . '<br />';
                                                                                    endif;

                                                                                    if($buyer['result']['user_city']):
                                                                                    print $buyer['result']['user_city'] . '<br />';
                                                                                    endif;

                                                                                    print $buyer['result']['user_mobile'];
                                                                                    ?>
                                                                                </div>

                                                                                <div class="col-sm-6 pull-right">
                                                                                    Shipping Address:<br />

                                                                                    <?php
                                                                                    $request    =   $this->data->user_address_single('id', $result['address_id']);

                                                                                    print $request['result']['address_firstname'] . '<br />';
                                                                                    print $request['result']['address_address'] . '<br />';
                                                                                    print $request['result']['address_city'] . '<br />';
                                                                                    
                                                                                    if($request['result']['address_postcode']):
                                                                                        print 'Postcode: ' . $request['result']['address_postcode'] . '<br />';
                                                                                    endif;

                                                                                    print 'HP: ' .$request['result']['address_phone'] . '<br />';

                                                                                    //print '<pre>';
                                                                                    //print_r($request);
                                                                                    //print '</pre>';
                                                                                    ?>
                                                                                </div>

                                                                            </div>

                                                                            <div class="modal-body">

                                                                              <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                                                                <tbody>

                                                                                  <tr>
                                                                                    <td align="center">&nbsp;</td>
                                                                                    <td align="center">Name</td>
                                                                                    <td align="center">Size / Qty</td>
                                                                                    <td align="center">Price</td>
                                                                                  </tr>
                                                                                  <?php foreach($detail as $detail): ?>
                                                                                  <tr>
                                                                                    <td align="center"><img src="/product/thumb/<?php print $detail['default_image'] ?>"></td>
                                                                                    <td align="center"><a href="/view/<?php print $detail['product_path'] ?>"><?php print $detail['product_name'] ?></a></td>
                                                                                    <td align="center"><?php print $detail['product_size'] .' / '. $detail['order_qty'] ?></td>
                                                                                    <td align="center"><?php print $this->tools->format_angka($detail['product_price'],0) ?></td>
                                                                                  </tr>
                                                                                  <?php endforeach; ?>
                                                                                </tbody>
                                                                              </table>

                                                                                
                                                                            </div>

                                                                            <?php if($result['order_notes']): ?>
                                                                            <div class="modal-footer pull-left bold">
                                                                                Order Notes:<br />
                                                                                <?php print $result['order_notes'] ?>
                                                                                <br clear="all">
                                                                            </div>
                                                                            <?php endif; ?>

                                                                            <div class="modal-footer">
                                                                                <button type="button" data-dismiss="modal" class="btn btn-outline dark">Close</button>
                                                                                <button type="button" class="btn green hidden">Save changes</button>
                                                                            </div>
                                                                        </div>                                                                        
                                                                    </td>
                                                                </tr>


                                                            <?php endforeach; ?>
          
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>





                                        </div>
                                        <!-- END SAMPLE TABLE PORTLET-->
                                    </div>
                                </div>
                                <!-- END PAGE BASE CONTENT -->

                                <div class="row">

                                    <?php if($total): ?>
                                    <div class="col-md-6 hidden">
                                        <button type="button" class="btn btn-danger btn-delete-checkbox" data-type="<?php print $page_identifier ?>"><i class="icon-trash"></i> Delete Selected</button>
                                     </div>
                                     <?php endif; ?>        

                                    <div class="col-md-6 text-right">

                                        <ul class="pagination pagination-sm">
                                        <?php 
                                        print $paging;
                                        ?>                       
                                        </ul>

                                    </div>                                                                 

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- END SIDEBAR CONTENT LAYOUT -->