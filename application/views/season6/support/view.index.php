<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Support'); ?></h1>
            </div>
            <div id="content_center">
                <?php
                    if(empty($department_list)):
                        echo '<div style="padding: 0 30px 0px 50px;"><div class="i_note">' . __('Currently no active support departments.') . '</div></div>';
                    else:
                        echo $css;
                        echo $js;
                        ?>
                        <div class="box-style1" style="margin-bottom:55px;">
                            <h2 class="title">
                                <?php echo __('Send support ticket'); ?>
                                <div style="float:right;"><a class="custom_button"
                                                             href="<?php echo $this->config->base_url; ?>support/my-tickets"><?php echo __('View My Tickets'); ?></a>
                                </div>
                            </h2>
                            <div class="entry">
                                <div class="form">
                                    <?php
                                        if(isset($error)){
                                            echo '<div class="e_note">' . $error . '</div>';
                                        }
                                        if(isset($success)){
                                            echo '<div class="s_note">' . $success . '</div>';
                                        }
                                    ?>
                                    <form method="post" action="<?php echo $this->config->base_url; ?>support"
                                          id="support_form" enctype="multipart/form-data">
                                        <table>
                                            <tr>
                                                <td style="width: 200px;"><?php echo __('Subject'); ?>
                                                    :
                                                </td>
                                                <td>
                                                    <input class="validate[required,minSize[3],maxSize[50]]" type="text"
                                                           name="title" id="title" value=""/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><?php echo __('Department'); ?>
                                                    :
                                                </td>
                                                <td>
                                                    <select name="department" id="department" class="validate[required]">
                                                        <option
                                                                value=""><?php echo __('Select Department'); ?></option>
                                                        <?php
                                                            foreach($department_list as $key => $department):
                                                                if($department['pay_per_incident']){
                                                                    $payment = ' (' . $department['pay_per_incident'] . ' ' . $this->website->translate_credits($department['payment_type'], $this->session->userdata(['user' => 'server'])) . ')';
                                                                } else{
                                                                    $payment = '';
                                                                }
                                                                ?>
                                                                <option
                                                                        value="<?php echo $department['id']; ?>"><?php echo $department['department_name'] . $payment; ?></option>
                                                            <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><?php echo __('Priority'); ?>
                                                    :
                                                </td>
                                                <td>
                                                    <select name="priority" id="priority" class="validate[required]">
                                                        <option
                                                                value=""><?php echo __('Select Priority'); ?></option>
                                                        <?php
                                                            foreach($priority_list as $key => $priority):
                                                                ?>
                                                                <option
                                                                        value="<?php echo $key; ?>"><?php echo $priority[1]; ?></option>
                                                            <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 200px;"><?php echo __('Character'); ?>
                                                    :
                                                </td>
                                                <td>
                                                    <select name="character" id="character" class="validate[required]">
                                                        <option
                                                                value=""><?php echo __('Select Character'); ?></option>
                                                        <?php
                                                            if($char_list):
                                                                foreach($char_list as $char):
                                                                    ?>
                                                                    <option
                                                                            value="<?php echo $char['name']; ?>"><?php echo $char['name']; ?></option>
                                                                <?php
                                                                endforeach;
                                                            endif;
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><textarea name="text" id="support_ticket"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td>Attachment:</td>
                                                <td>
                                                    <input name="files[]" id="files" type="file" multiple/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div id="fileList"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                    <button type="submit" name="submit_ticket"
                                                            class="button-style"><?php echo __('Submit'); ?></button>
                                                </td>
                                            </tr>
                                        </table>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $('#files').on('change', function () {
                                    var fileList = $("#fileList");
                                    var files_attached = '<ul class="style3">';
                                    fileList.empty();

                                    for (var i = 0; i < this.files.length; i++) {
                                        var file = this.files[i];
                                        files_attached += '<li>' + file.name + '</li>';
                                    }
                                    files_attached += '</ul>';
                                    fileList.append(files_attached);
                                });

                                $("#support_form").validationEngine();
                                $("#support_ticket").cleditor({
                                    width: "600px",
                                    controls: "bold italic underline strikethrough subscript superscript | font size " +
                                    "style | color highlight removeformat | bullets numbering | " +
                                    "alignleft center alignright justify | undo redo ",
                                    colors: "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
                                    "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
                                    "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
                                    "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
                                    "666 900 C60 C93 990 090 399 33F 60C 939 " +
                                    "333 600 930 963 660 060 366 009 339 636 " +
                                    "000 300 630 633 330 030 033 006 309 303",
                                    fonts: "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
                                    "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
                                    sizes: "1,2,3,4,5,6,7",
                                    styles: [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
                                        ["Header 3", "<h3>"], ["Header 4", "<h4>"], ["Header 5", "<h5>"],
                                        ["Header 6", "<h6>"]],
                                    useCSS: false
                                });
                            });
                        </script>
                    <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>