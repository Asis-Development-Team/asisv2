                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        <h1 class="hidden">Order</h1>

                        <ol class="breadcrumb pull-left">
                            <li>
                                <a href="/admin/dashboard">Dashboard</a>
                            </li>
                            <li class="active">Data Static Pages</li>
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
                                                    <i class="fa fa-databasex"></i>Data Static Pages (<span class="total-entry"><?php print $total; ?></span>) 
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
                                                                    <i class=""></i> Page</th>
                                                                <th> </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php 
                                                            foreach($result as $result):

                                                            ?>
                                                            <tr class="parent-<?=$result['static_id']?> counter">


                                                                <td class="center hidden">
                                                                    <input type="checkbox" class="checkboxes checkbox-delete" value="<?php print $result['static_id'] ?>" id="checkbox-delete-<?php print $result['static_id'] ?>" name="multichecked[]" />
                                                                </td>

                                                                <td>
                                                                    <?php print $result['static_title'] ?>
                                                                </td>

                                                                <td>

                                                                    <div class="text-center">
                                                                    
                                                                    <a href="/admin/setting/pages/edit/?id=<?php print $result['static_id'] ?>" data-container="body" data-placement="top" data-original-title="Edit" class="tooltips"><i class="fa fa-edit"></i></a>
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