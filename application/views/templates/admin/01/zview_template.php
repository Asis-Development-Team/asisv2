                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                        <h1>Dashboard 2</h1>
                        <ol class="breadcrumb">
                            <li>
                                <a href="#">Home</a>
                            </li>
                            <li class="active">Dashboard</li>
                        </ol>
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
                                                    <i class="fa fa-shopping-cart"></i>Advance Table </div>
                                                <div class="tools">
                                                    <a href="javascript:;" class="collapse"> </a>
                                                    <a href="#portlet-config" data-toggle="modal" class="config"> </a>
                                                    <a href="javascript:;" class="reload"> </a>
                                                    <a href="javascript:;" class="remove"> </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-scrollable">
                                                    <table class="table table-striped table-bordered table-advance table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <i class="fa fa-briefcase"></i> From </th>
                                                                <th class="hidden-xs text-center">
                                                                    <i class="fa fa-question"></i> Descrition </th>
                                                                <th class="text-center">
                                                                    <i class="fa fa-bookmark"></i> Total </th>
                                                                <th> </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php for($i=0;$i<=5;$i++): ?>
                                                            <tr>
                                                                <td>
                                                                    <a href="javascript:;"> Pixel Ltd </a>
                                                                </td>
                                                                <td class="hidden-xs"> Server hardware purchase </td>
                                                                <td> 52560.10$
                                                                    <span class="label label-sm label-success label-mini"> Paid </span>
                                                                </td>
                                                                <td>
                                                                    <div class="text-center">
                                                                        <a href="javascript:;" class="btn dark btn-sm btn-outline sbold uppercase">
                                                                        <i class="fa fa-share"></i> View </a>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                            <?php endfor; ?>
          
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <ul class="pagination pagination-sm">
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-angle-left"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;"> 1 </a>
                                                    </li>
                                                    <li class="active">
                                                        <a href="javascript:;"> 2 </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;"> 3 </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;"> 4 </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;"> 5 </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;"> 6 </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;">
                                                            <i class="fa fa-angle-right"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>


                                        </div>
                                        <!-- END SAMPLE TABLE PORTLET-->
                                    </div>
                                </div>
                                <!-- END PAGE BASE CONTENT -->
                            </div>
                        </div>
                    </div>
                    <!-- END SIDEBAR CONTENT LAYOUT -->