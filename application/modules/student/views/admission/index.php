<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class="head-title"><i class="fa fa-users"></i><small> <?php echo $this->lang->line('manage'); ?> <?php echo $this->lang->line('online'); ?> <?php echo $this->lang->line('admission'); ?></small></h3>
                <ul class="nav navbar-right panel_toolbox">                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content quick-link">
                <strong> <?php echo $this->lang->line('quick_link'); ?>: </strong>
                <?php if(has_permission(VIEW, 'student', 'type')){ ?>
                    <a href="<?php echo site_url('student/type/index'); ?>"> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('type'); ?></a> |
                 <?php } ?>
                <?php if(has_permission(VIEW, 'student', 'group')){ ?>
                    <a href="<?php echo site_url('student/group/index'); ?>"> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('group'); ?></a> |
                 <?php } ?>
                <?php if(has_permission(ADD, 'student', 'student')){ ?>
                    <a href="<?php echo site_url('student/add'); ?>"><?php echo $this->lang->line('admit'); ?> <?php echo $this->lang->line('student'); ?></a> |
                 <?php } ?>
                <?php if(has_permission(ADD, 'student', 'student')){ ?>
                    <a href="<?php echo site_url('student/advanced'); ?>"><?php echo $this->lang->line('advanced'); ?> <?php echo $this->lang->line('admission'); ?></a> |
                 <?php } ?>
                <?php if(has_permission(ADD, 'student', 'bulk')){ ?>
                    <a href="<?php echo site_url('student/bulk/add'); ?>"><?php echo $this->lang->line('bulk'); ?> <?php echo $this->lang->line('admission'); ?></a> |
                 <?php } ?>
                <?php if(has_permission(VIEW, 'student', 'student')){ ?>
                    <a href="<?php echo site_url('student/index'); ?>"><?php echo $this->lang->line('manage_student'); ?></a> |                   
                 <?php } ?>
                <?php if(has_permission(VIEW, 'student', 'activity')){ ?>
                    <a href="<?php echo site_url('student/activity/index'); ?>"> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('activity'); ?></a>                    
                 <?php } ?>
            </div>
            <div class="x_content">
                <div class="" data-example-id="togglable-tabs">
                    
                    <ul  class="nav nav-tabs bordered">
                        <li class="<?php if(isset($list)){ echo 'active'; }?>"><a href="#tab_admission_list"   role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-list-ol"></i> <?php echo $this->lang->line('admission'); ?> <?php echo $this->lang->line('list'); ?></a> </li> 
                        <?php if(isset($edit)){ ?>
                            <li  class="active"><a href="#tab_edit_admission"  role="tab"  data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('approve'); ?> <?php echo $this->lang->line('admission'); ?></a> </li>                          
                        <?php } ?>  
                            
                        <li class="li-class-list">
                            <select  class="form-control col-md-7 col-xs-12" onchange="get_student_by_class(this.value);">
                                <?php if($this->session->userdata('role_id') != STUDENT){ ?>    
                                    <option value="<?php echo site_url('student/index/'); ?>">--<?php echo $this->lang->line('select'); ?>--</option> 
                                <?php } ?>
                                <?php foreach($classes as $obj ){ ?>
                                    <?php if($this->session->userdata('role_id') == STUDENT && $this->session->userdata('class_id') == $obj->id){ ?>
                                        <option value="<?php echo site_url('student/advanced/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                    <?php }elseif($this->session->userdata('role_id') != STUDENT){ ?>
                                        <option value="<?php echo site_url('student/advanced/'.$obj->id); ?>" <?php if(isset($class_id) && $class_id == $obj->id){ echo 'selected="selected"';} ?> ><?php echo $this->lang->line('class'); ?> <?php echo $obj->name; ?></option>
                                    <?php } ?>
                                <?php } ?>                                            
                            </select>
                        </li>
                    </ul>
                    <br/>
                    
                    <div class="tab-content">
                        <div  class="tab-pane fade in <?php if(isset($list)){ echo 'active'; }?>" id="tab_admission_list" >
                            <div class="x_content">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('sl_no'); ?></th>                                        
                                        <th><?php echo $this->lang->line('photo'); ?></th>
                                        <th><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?> </th>
                                        <th><?php echo $this->lang->line('action'); ?></th>                                            
                                    </tr>
                                </thead>
                                <tbody>   
                                    <?php $count = 1; if(isset($admissions) && !empty($admissions)){ ?>
                                        <?php foreach($admissions as $obj){ ?>
                                        <tr>
                                            <td><?php echo $count++; ?></td>  
                                            <td>
                                                <?php  if($obj->photo != ''){ ?>
                                                    <img src="<?php echo UPLOAD_PATH; ?>/admission-photo/<?php echo $obj->photo; ?>" alt="" width="70" /> 
                                                <?php }else{ ?>
                                                    <img src="<?php echo IMG_URL; ?>/default-user.png" alt="" width="70" /> 
                                                <?php } ?>  
                                            </td>
                                            <td><?php echo $obj->name; ?></td>
                                            <td><?php echo $obj->class_name; ?></td>
                                            <td>
                                                <?php  if($obj->status == 0){ ?>
                                                    <a href="javascript:void(0);" class="btn btn-default btn-xs"> <?php echo $this->lang->line('new'); ?> </a>
                                                <?php  }elseif($obj->status == 1){ ?>
                                                    <a href="javascript:void(0);" class="btn btn-info btn-xs"><?php echo $this->lang->line('waiting'); ?> </a>  
                                                <?php  }elseif($obj->status == 2){ ?>
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs"><?php echo $this->lang->line('declined'); ?> </a>  
                                                <?php  }elseif($obj->status == 3){ ?>
                                                    <a href="javascript:void(0);" class="btn btn-success btn-xs"><?php echo $this->lang->line('approved'); ?> </a>  
                                                <?php  } ?>
                                            </td>
                                            <td>
                                                <?php if(has_permission(EDIT, 'student', 'admission')){ ?>
                                                
                                                    <?php if($obj->status == 0){ ?>
                                                        <a href="<?php echo site_url('student/admission/approve/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_proceed'); ?>');" class="btn btn-success btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('approve'); ?> </a>
                                                        <a href="<?php echo site_url('student/admission/waiting/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_proceed'); ?>');" class="btn btn-info btn-xs"><i class="fa fa-spinner"></i> <?php echo $this->lang->line('waiting'); ?> </a>
                                                        <a href="<?php echo site_url('student/admission/decline/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_proceed'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> <?php echo $this->lang->line('decline'); ?> </a>
                                                    <?php }elseif($obj->status == 1){ ?>
                                                        <a href="<?php echo site_url('student/admission/approve/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_proceed'); ?>');" class="btn btn-success btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('approve'); ?> </a>
                                                        <a href="<?php echo site_url('student/admission/decline/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_proceed'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> <?php echo $this->lang->line('decline'); ?> </a>
                                                    <?php }elseif($obj->status == 2){ ?>
                                                        <a href="<?php echo site_url('student/admission/waiting/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_proceed'); ?>');" class="btn btn-info btn-xs"><i class="fa fa-spinner"></i> <?php echo $this->lang->line('waiting'); ?> </a>
                                                    <?php } ?>
                                                    
                                                <?php } ?>
                                                    
                                                <?php if(has_permission(VIEW, 'student', 'admission')){ ?>
                                                    <a  onclick="get_admission_modal(<?php echo $obj->id; ?>);"  data-toggle="modal" data-target=".bs-admission-modal-lg"  class="btn btn-success btn-xs"><i class="fa fa-eye"></i> <?php echo $this->lang->line('view'); ?> </a>
                                                <?php } ?>
                                                <?php if(has_permission(DELETE, 'student', 'admission')){ ?>
                                                    <a href="<?php echo site_url('student/admission/delete/'.$obj->id); ?>" onclick="javascript: return confirm('<?php echo $this->lang->line('confirm_alert'); ?>');" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        
                        <?php if(isset($edit)){ ?>
                        <div class="tab-pane fade in active" id="tab_edit_admission">
                              <div class="x_content"> 
                            <?php echo form_open_multipart(site_url('student/admission/approve/'. $admission->id), array('name' => 'edit', 'id' => 'edit', 'class'=>'form-horizontal form-label-left'), ''); ?>
                             
                               <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('basic'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                
                                <div class="row">                                     
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="name"  id="name" value="<?php echo isset($admission->name) ?  $admission->name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('name'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="admission_no"><?php echo $this->lang->line('admission_no'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="admission_no"  id="admission_no" value="<?php echo isset($admission->admission_no) ?  $admission->admission_no : ''; ?>" placeholder="<?php echo $this->lang->line('admission_no'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('admission_no'); ?></div> 
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="admission_date"><?php echo $this->lang->line('admission'); ?> <?php echo $this->lang->line('date'); ?><span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="admission_date"  id="edit_admission_date" value="<?php echo isset($admission->created_at) ?   date($this->gsms_setting->sms_date_format, strtotime($admission->created_at)) : ''; ?>" placeholder="<?php echo $this->lang->line('admission'); ?> <?php echo $this->lang->line('date'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('admission_date'); ?></div> 
                                        </div>
                                    </div>   
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label  for="dob"><?php echo $this->lang->line('birth_date'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="dob"  id="edit_dob" value="<?php echo isset($admission->dob) ?  date($this->gsms_setting->sms_date_format, strtotime($admission->dob)) : ''; ?>" placeholder="<?php echo $this->lang->line('birth_date'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('dob'); ?></div>
                                         </div>
                                    </div>
                                </div>
                                <div class="row">                                     
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="gender"><?php echo $this->lang->line('gender'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12"  name="gender"  id="gender" required="required">
                                               <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                               <?php $genders = get_genders(); ?>
                                               <?php foreach($genders as $key=>$value){ ?>
                                                   <option value="<?php echo $key; ?>" <?php if($admission->gender == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                               <?php } ?>
                                           </select>
                                           <div class="help-block"><?php echo form_error('gender'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="blood_group"><?php echo $this->lang->line('blood_group'); ?></label>
                                              <select  class="form-control col-md-7 col-xs-12" name="blood_group" id="blood_group">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php $bloods = get_blood_group(); ?>
                                                <?php foreach($bloods as $key=>$value){ ?>
                                                    <option value="<?php echo $key; ?>" <?php if($admission->blood_group == $key){ echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                                <?php } ?>
                                                </select>
                                            <div class="help-block"><?php echo form_error('blood_group'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                              <label for="religion"><?php echo $this->lang->line('religion'); ?></label>
                                              <input  class="form-control col-md-7 col-xs-12"  name="religion"  id="add_religion" value="<?php echo isset($admission->religion) ?  $admission->religion : ''; ?>" placeholder="<?php echo $this->lang->line('religion'); ?>" type="text" autocomplete="off">
                                               <div class="help-block"><?php echo form_error('religion'); ?></div>
                                         </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                              <label for="caste"><?php echo $this->lang->line('caste'); ?></label>
                                              <input  class="form-control col-md-7 col-xs-12"  name="caste"  id="add_caste" value="<?php echo isset($admission->caste) ?  $admission->caste : ''; ?>" placeholder="<?php echo $this->lang->line('caste'); ?>" type="text" autocomplete="off">
                                               <div class="help-block"><?php echo form_error('caste'); ?></div>
                                         </div>
                                    </div>
                                </div>                                  
                                <div class="row">                                     
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="phone"><?php echo $this->lang->line('phone'); ?></label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="phone"  id="add_phone" value="<?php echo isset($admission->phone) ?  $admission->phone : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('phone'); ?></div>
                                         </div>
                                    </div>                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="national_id"><?php echo $this->lang->line('national_id'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="national_id"  id="national_id" value="<?php echo isset($admission->national_id) ?  $admission->national_id : ''; ?>" placeholder="<?php echo $this->lang->line('national_id'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('national_id'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                  
                                       
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('academic'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row">                                      
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="academic_year_id"><?php echo $this->lang->line('academic_year'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12 quick-field" name="academic_year_id" id="academic_year_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($academic_years as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>"><?php echo $obj->session_year; if($obj->is_running){ echo ' ['.$this->lang->line('running_year').']'; } ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('academic_year_id'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="type_id"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('type'); ?></label>
                                             <select  class="form-control col-md-7 col-xs-12 quick-field" name="type_id" id="type_id">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($types as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($admission->type_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->type; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('type_id'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="class_id"><?php echo $this->lang->line('class'); ?> <span class="required">*</span></label>
                                             <select  class="form-control col-md-7 col-xs-12 quick-field" name="class_id" id="class_id" required="required" onchange="get_section_by_class(this.value, '');">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($classes as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($admission->class_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('class_id'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="section_id"><?php echo $this->lang->line('section'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="section_id" id="section_id" required="required">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                            </select>
                                            <div class="help-block"><?php echo form_error('section_id'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                    
                                <div class="row">
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="group_id"><?php echo $this->lang->line('group'); ?> </label>
                                            <select  class="form-control col-md-7 col-xs-12" name="group_id" id="group_id">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>                                               
                                                <?php foreach($groups as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php if($admission->group_id == $obj->id){ echo 'selected="selected"';} ?>><?php echo $obj->group; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('group_id'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="roll_no"><?php echo $this->lang->line('roll_no'); ?> <span class="required">*</span></label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="roll_no"  id="roll_no" value="" placeholder="<?php echo $this->lang->line('roll_no'); ?>" required="required" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('roll_no'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="registration_no"><?php echo $this->lang->line('registration_no'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="registration_no"  id="registration_no" value="" placeholder="<?php echo $this->lang->line('registration_no'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('registration_no'); ?></div>
                                         </div>
                                     </div>                                     
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="discount_id"><?php echo $this->lang->line('discount'); ?></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="discount_id" id="edit_discount_id">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($discounts as $obj){ ?>                                                    
                                                    <option value="<?php echo $obj->id; ?>" ><?php echo $obj->title; ?> [<?php echo $obj->amount; ?>%]</option>                                                   
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('discount_id'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="second_language"><?php echo $this->lang->line('second'); ?> <?php echo $this->lang->line('language'); ?></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="second_language"  id="second_language" value="<?php echo isset($admission->second_language) ?  $admission->second_language : ''; ?>" placeholder="<?php echo $this->lang->line('second'); ?> <?php echo $this->lang->line('second'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('second_language'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                  
                                   
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div> 
                                <div class="row">  
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_name"><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_name"  id="father_name" value="<?php echo isset($admission->father_name) ?  $admission->father_name : ''; ?>" placeholder="<?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_name'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_phone"><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('phone'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_phone"  id="father_phone" value="<?php echo isset($admission->father_phone) ?  $admission->father_phone : ''; ?>" placeholder="<?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('phone'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_phone'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_education"><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('education'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_education"  id="father_education" value="<?php echo isset($admission->father_education) ?  $admission->father_education : ''; ?>" placeholder="<?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('education'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_education'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_profession"><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('profession'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_profession"  id="father_profession" value="<?php echo isset($admission->father_profession) ?  $admission->father_profession : ''; ?>" placeholder="<?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('profession'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_profession'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="father_designation"><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('designation'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="father_designation"  id="father_designation" value="<?php echo isset($admission->father_designation) ?  $admission->father_designation : ''; ?>" placeholder="<?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('designation'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('father_designation'); ?></div>
                                         </div>
                                     </div>                                     
                                    
                                </div>
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div> 
                                <div class="row">  
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_name"><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('name'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_name"  id="mother_name" value="<?php echo isset($admission->mother_name) ?  $admission->mother_name : ''; ?>" placeholder="<?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('name'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_name'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_phone"><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('phone'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_phone"  id="mother_phone" value="<?php echo isset($admission->mother_phone) ?  $admission->mother_phone : ''; ?>" placeholder="<?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('phone'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_phone'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_education"><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('education'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_education"  id="mother_education" value="<?php echo isset($admission->mother_education) ?  $admission->mother_education : ''; ?>" placeholder="<?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('education'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_education'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_profession"><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('profession'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_profession"  id="mother_profession" value="<?php echo isset($admission->mother_profession) ?  $admission->mother_profession : ''; ?>" placeholder="<?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('profession'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_profession'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="mother_designation"><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('designation'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="mother_designation"  id="mother_designation" value="<?php echo isset($admission->mother_designation) ?  $admission->mother_designation : ''; ?>" placeholder="<?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('designation'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('mother_designation'); ?></div>
                                         </div>
                                     </div>                                                                     
                                </div>
                                  
                                    
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                        <input type="hidden" name="is_guardian" id="is_guardian" value="<?php echo $admission->is_guardian; ?>" />
                                    </div>
                                </div>
                                <?php if($admission->guardian_id){ ?>                                
                                    <div class="row"> 
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">                                            
                                            <label for="guardian_id"><?php echo $this->lang->line('guardian'); ?> <span class="required">*</span></label>
                                            <select  class="form-control col-md-7 col-xs-12 quick-field" name="guardian_id" id="guardian_id" onchange="get_guardian_by_id(this.value);">
                                                <option value="">--<?php echo $this->lang->line('select'); ?>--</option>
                                                <?php foreach($guardians as $obj){ ?>
                                                    <option value="<?php echo $obj->id; ?>" <?php echo isset($admission) && $admission->guardian_id == $obj->id ?  'selected="selected"' : ''; ?>><?php echo $obj->name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="help-block"><?php echo form_error('guardian_id'); ?></div>                                           
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="relation_with"><?php echo $this->lang->line('relation_with'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="relation_with"  id="relation_with"  value="<?php echo isset($admission->gud_relation) ?  $admission->gud_relation : ''; ?>" placeholder="<?php echo $this->lang->line('relation_with'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('relation_with'); ?></div>
                                        </div>
                                    </div>                                     
                                </div>
                                  
                                <?php }else{ ?>                                    
                                
                                <div class="row"> 
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="gud_name"><?php echo $this->lang->line('name'); ?> <span class="required">*</span></label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="gud_name"  id="gud_name" value="<?php echo isset($admission->gud_name) ?  $admission->gud_name : ''; ?>" placeholder="<?php echo $this->lang->line('name'); ?>" required="required" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('gud_name'); ?></div>
                                         </div>
                                     </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="relation_with"><?php echo $this->lang->line('relation_with'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="relation_with"  id="relation_with" value="<?php echo isset($admission->gud_relation) ?  $admission->gud_relation : ''; ?>" placeholder="<?php echo $this->lang->line('relation_with'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('relation_with'); ?></div>
                                        </div>
                                    </div>   
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="gud_phone"><?php echo $this->lang->line('phone'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="gud_phone"  id="gud_phone" value="<?php echo isset($admission->gud_phone) ?  $admission->gud_phone : ''; ?>" placeholder="<?php echo $this->lang->line('phone'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('gud_phone'); ?></div>
                                        </div>
                                    </div>                                     
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label for="gud_email"><?php echo $this->lang->line('email'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="gud_email"  id="gud_email" value="<?php echo isset($admission->gud_email) ?  $admission->gud_email : ''; ?>" placeholder="<?php echo $this->lang->line('email'); ?>" required="required" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('gud_email'); ?></div>
                                        </div>
                                    </div>
                                </div>  
                               <div class="row">
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="gud_national_id"><?php echo $this->lang->line('national_id'); ?> </label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="gud_national_id"  id="gud_national_id" value="<?php echo isset($admission->gud_national_id) ?  $admission->gud_national_id : ''; ?>" placeholder="<?php echo $this->lang->line('national_id'); ?>" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('gud_national_id'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="gud_profession"><?php echo $this->lang->line('profession'); ?> </label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="gud_profession"  id="gud_profession" value="<?php echo isset($admission->gud_profession) ?  $admission->gud_profession : ''; ?>" placeholder="<?php echo $this->lang->line('profession'); ?>" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('gud_profession'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="gud_religion"><?php echo $this->lang->line('religion'); ?> </label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="gud_religion"  id="gud_religion" value="<?php echo isset($admission->gud_religion) ?  $admission->gud_religion : ''; ?>" placeholder="<?php echo $this->lang->line('religion'); ?>" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('gud_religion'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <label for="gud_other_info"><?php echo $this->lang->line('other_info'); ?> </label>
                                             <input  class="form-control col-md-7 col-xs-12"  name="gud_other_info"  id="gud_other_info" value="<?php echo isset($admission->gud_other_info) ?  $admission->gud_other_info : ''; ?>" placeholder="<?php echo $this->lang->line('other_info'); ?>" type="text" autocomplete="off">
                                             <div class="help-block"><?php echo form_error('gud_other_info'); ?></div>
                                         </div>
                                     </div>
                                    
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                             <label for="gud_present_address"><?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?> </label>
                                              <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="gud_present_address"  id="gud_present_address"  placeholder="<?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($admission->gud_present_address) ?  $admission->gud_present_address : ''; ?></textarea>
                                              <div class="help-block"><?php echo form_error('gud_present_address'); ?></div>
                                         </div>
                                     </div>                                    
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                            <label for="gud_permanent_address"><?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?></label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="gud_permanent_address"  id="gud_permanent_address"  placeholder="<?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($admission->gud_permanent_address) ?  $admission->gud_permanent_address : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('gud_permanent_address'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                <?php } ?>   
                                
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row"> 
                                    
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                             <label for="present_address"><?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?> </label>
                                              <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="present_address"  id="add_present_address"  placeholder="<?php echo $this->lang->line('present'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($admission->present_address) ?  $admission->present_address : ''; ?></textarea>
                                              <div class="help-block"><?php echo form_error('present_address'); ?></div>
                                         </div>
                                     </div>                                    
                                     <div class="col-md-6 col-sm-6 col-xs-12">
                                         <div class="item form-group">
                                            <label for="permanent_address"><?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?></label>
                                            <textarea  class="form-control col-md-7 col-xs-12 textarea-4column"  name="permanent_address"  id="add_permanent_address"  placeholder="<?php echo $this->lang->line('permanent'); ?> <?php echo $this->lang->line('address'); ?>"><?php echo isset($admission->permanent_address) ?  $admission->permanent_address : ''; ?></textarea>
                                            <div class="help-block"><?php echo form_error('permanent_address'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                                                
                         
                           
                                <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5  class="column-title"><strong><?php echo $this->lang->line('previous'); ?> <?php echo $this->lang->line('school'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="previous_school"><?php echo $this->lang->line('previous'); ?> <?php echo $this->lang->line('school'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="previous_school"  id="previous_school" value="<?php echo isset($admission->previous_school) ?  $admission->previous_school : ''; ?>" placeholder="<?php echo $this->lang->line('previous'); ?> <?php echo $this->lang->line('school'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('previous_school'); ?></div>
                                         </div>
                                    </div>                                    
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="previous_class"><?php echo $this->lang->line('previous'); ?> <?php echo $this->lang->line('class'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="previous_class"  id="previous_class" value="<?php echo isset($admission->previous_class) ?  $admission->previous_class : ''; ?>" placeholder="<?php echo $this->lang->line('previous'); ?> <?php echo $this->lang->line('class'); ?>"  type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('previous_class'); ?></div>
                                         </div>
                                    </div>                             
                                </div>
                             
                                
                               <div class="row">                  
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <h5 class="column-title"><strong><?php echo $this->lang->line('other'); ?> <?php echo $this->lang->line('information'); ?>:</strong></h5>
                                    </div>
                                </div>    
                                <div class="row">                       
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="email"><?php echo $this->lang->line('email'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="email"   id="email" value="<?php echo isset($admission->email) ?  $admission->email : ''; ?>" required="required" placeholder="<?php echo $this->lang->line('email'); ?>" type="email" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('email'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="password"><?php echo $this->lang->line('password'); ?> <span class="required">*</span></label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="password"  id="password" value="" required="required" placeholder="<?php echo $this->lang->line('password'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('password'); ?></div>
                                         </div>
                                     </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                            <label for="health_condition"><?php echo $this->lang->line('health_condition'); ?> </label>
                                            <input  class="form-control col-md-7 col-xs-12"  name="health_condition"  id="health_condition" value="<?php echo isset($admission->health_condition) ?  $admission->health_condition : ''; ?>" placeholder="<?php echo $this->lang->line('health_condition'); ?>" type="text" autocomplete="off">
                                            <div class="help-block"><?php echo form_error('health_condition'); ?></div>
                                         </div>
                                     </div>
                                </div>
                                <div class="row">     
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="item form-group">
                                           <label for="other_info"><?php echo $this->lang->line('other_info'); ?></label> 
                                           <textarea  class="form-control col-md-6 col-xs-12 textarea-4column"  name="other_info"  id="other_info" placeholder="<?php echo $this->lang->line('other_info'); ?>"></textarea>
                                           <div class="help-block"><?php echo form_error('other_info'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-12">
                                        <div class="item form-group">
                                            <label ><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                            <div class="btn btn-default btn-file">
                                                <i class="fa fa-paperclip"></i> <?php echo $this->lang->line('upload'); ?>
                                                <input  class="form-control col-md-7 col-xs-12"  name="photo"  id="photo" type="file">
                                            </div>
                                            <div class="text-info"><?php echo $this->lang->line('valid_file_format_img'); ?></div>
                                            <div class="help-block"><?php echo form_error('photo'); ?></div>
                                        </div>
                                    </div>
                                     <div class="col-md-3 col-sm-3 col-xs-12">
                                         <div class="item form-group">
                                             <input type="hidden" name="prev_photo" id="prev_photo" value="<?php echo $admission->photo; ?>" />
                                            <?php if($admission->photo){ ?>
                                            <img src="<?php echo UPLOAD_PATH; ?>/admission-photo/<?php echo $admission->photo; ?>" alt="" width="70" /><br/><br/>
                                            <?php } ?>
                                         </div>
                                     </div>                                    
                                </div>
                                                                
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-3">
                                        <input type="hidden" name="id" id="id" value="<?php echo $admission->id; ?>" />
                                         <input type="hidden" name="role_id" id="role_id" value="<?php echo STUDENT; ?>" />
                                        <a href="<?php echo site_url('student'); ?>" class="btn btn-primary"><?php echo $this->lang->line('cancel'); ?></a>
                                        <button id="send" type="submit" class="btn btn-success"><?php echo $this->lang->line('approve'); ?></button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                
                            </div>
                        </div>  
                        <?php } ?>
              
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bs-admission-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title"><?php echo $this->lang->line('admission'); ?> <?php echo $this->lang->line('information'); ?></h4>
        </div>
        <div class="modal-body fn_admission_data">
            
        </div>       
      </div>
    </div>
</div>
<script type="text/javascript">
         
    function get_admission_modal(admission_id){
         
        $('.fn_admission_data').html('<p style="padding: 20px;"><p style="padding: 20px;text-align:center;"><img src="<?php echo IMG_URL; ?>loading.gif" /></p>');
        $.ajax({       
          type   : "POST",
          url    : "<?php echo site_url('student/admission/get_single_admission'); ?>",
          data   : {admission_id : admission_id},  
          success: function(response){                                                   
             if(response)
             {
                $('.fn_admission_data').html(response);
             }
          }
       });
    }
</script>


<!-- bootstrap-datetimepicker -->
<link href="<?php echo VENDOR_URL; ?>datepicker/datepicker.css" rel="stylesheet">
 <script src="<?php echo VENDOR_URL; ?>datepicker/datepicker.js"></script>
 
 <script type="text/javascript">     
  
  $('#edit_admission_date').datepicker(); 
  $('#edit_dob').datepicker({ startView: 2 });
 
  
    <?php if(isset($admission) && isset($admission)){ ?>
        get_section_by_class('<?php echo $admission->class_id; ?>', '');
    <?php } ?>
    
    function get_section_by_class(class_id, section_id){       
               
        $.ajax({       
            type   : "POST",
            url    : "<?php echo site_url('ajax/get_section_by_class'); ?>",
            data   : { class_id : class_id , section_id: section_id},               
            async  : false,
            success: function(response){                                                   
               if(response)
               {
                  $('#section_id').html(response);
               }
            }
        });         
    }

</script>


 <!-- datatable with buttons -->
 <script type="text/javascript">
        $(document).ready(function() {
          $('#datatable-responsive').DataTable( {
              dom: 'Bfrtip',
              iDisplayLength: 15,
              buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdfHtml5',
                  'pageLength'
              ],
              search: true,              
              responsive: true
          });
        });
       
    function get_student_by_class(url){          
        if(url){
            window.location.href = url; 
        }
    }        

    $("#edit").validate();   
    
</script>
 