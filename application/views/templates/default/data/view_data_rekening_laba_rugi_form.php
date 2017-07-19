                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="/dashboard">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active blue"><?php print $page_title ?></span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">


                        <div class="col-md-12">


                                    <div class="tab-pane" id="tab_1">

                                        <div class="portlet light bordered">

                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="font-blue-hoki"></i>
                                                    <span class="caption-subject font-blue-hoki bold uppercase"><?php print (@$_GET['id']) ? 'Edit ' : 'Tambah '; ?><?php print $page_title_global ; ?></span>
                                                    
                                                </div>
                                                <div class="tools">


                                                <a href="/<?php print $this->uri->segment('1') ?>/<?php print $page_url_main ?>" style="margin:0 0 20px 0">
                                                <button class="btn blue"><i class="fa fa-arrow-left "></i> Kembali</button>
                                                </a>

                                                <a href="/data/<?php print $page_url ?>" style="margin:0 0 20px 0">
                                                <button class="btn blue"><i class="fa fa-edit "></i> Tambah</button>
                                                </a>  


                                                </div>
                                            </div>


                                            <?php 

                                            $this->load->view('templates/'.$this->template->data['template_admin'].'/data/view_data_rekening_laba_rugi_form_include');
                                            ?>



                                        </div>


                                    </div>



                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->