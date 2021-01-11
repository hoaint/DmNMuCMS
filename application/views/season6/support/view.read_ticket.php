<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
    <div id="content">
        <div id="box1">
            <div class="title1">
                <h1><?php echo __('Support'); ?></h1>
            </div>
            <div id="content_center">
                <div class="box-style1" style="margin-bottom:55px;">
                    <?php
                        if(isset($error)):
                            echo '<div style="padding: 0 30px 0px 50px;"><div class="e_note">' . $error . '</div></div>';
                        else:
                            ?>
                            <h2 class="title">
                                <?php echo $ticket_data['subject']; ?>
                            </h2>
                            <div class="entry">
                                <table class="ticket left" style="width:370px;" cellspacing="0">
                                    <thead>
                                    <th><?php echo $this->session->userdata(['user' => 'username']); ?></th>
                                    <th style="text-align:right;"><?php echo date('d/m/Y g:i a', $ticket_data['create_time']); ?></th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="2"><?php echo $ticket_data['message']; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <?php
                                    $last_reply = $this->Msupport->get_last_reply_time($ticket_data['id']);
                                    if($last_reply != false){
                                        $time_elapsed = $this->Msupport->date_diff($ticket_data['create_time'], $last_reply['reply_time']);
                                    } else{
                                        $time_elapsed = 'None';
                                    }
                                ?>
                                <table class="ticket right" style="width:200px;" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td><?php echo __('Request Id'); ?></td>
                                        <td><?php echo $ticket_data['id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Status'); ?></td>
                                        <td><span
                                                    id="ticket_status"><?php echo $this->Msupport->readable_status($ticket_data['status']); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Time Elapsed'); ?></td>
                                        <td><?php echo $time_elapsed; ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Department'); ?></td>
                                        <td><?php echo $this->Msupport->get_department_name($ticket_data['department']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Priority'); ?></td>
                                        <td><?php echo $this->Msupport->generate_priority($ticket_data['priority'], false, true); ?></td>
                                    </tr>
                                    <?php if($ticket_data['status'] != 3){ ?>
                                        <tr id="tr_resolved">
                                            <td colspan="2" style="text-align:center;"><a href="" id="mark_resolved"
                                                                                          data-id="<?php echo $ticket_data['id']; ?>"><?php echo __('Mark As Resolved'); ?></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                        if($ticket_data['attachment'] != null){
                                            $attachment = @unserialize($ticket_data['attachment']);
                                            if($attachment != false){
                                                ?>
                                                <tr>
                                                    <td colspan="2" style="text-align:center;">
                                                        <div><?php echo __('Attached Files'); ?></div>
                                                        <div>
                                                            <?php
                                                                $ul = '<ul style="line-style:none;list-style-type: none;padding-right:20px;">';
                                                                $i = 0;
                                                                foreach($attachment AS $files){
                                                                    $i++;
                                                                    $ul .= '<li style="display: inline-block;"><a href="' . $this->config->base_url . 'assets/uploads/attachment/' . $files . '" target="_blank">File ' . $i . '</a></li> ';
                                                                    if($i % 2 == 0){
                                                                        $ul .= '<br />';
                                                                    }
                                                                }
                                                                $ul .= '</ul>';
                                                                echo $ul;
                                                            ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    ?>
                                    </tbody>
                                </table>
                                <?php if($ticket_data['status'] != 3){ ?>
                                    <script>
                                        $(document).ready(function () {
                                            $('#mark_resolved').on('click', function (e) {
                                                e.preventDefault();
                                                $.ajax({
                                                    url: DmNConfig.base_url + 'support/mark-resolved',
                                                    data: {id: $('#mark_resolved').attr("data-id")},
                                                    beforeSend: function () {
                                                        App.showLoader();
                                                    },
                                                    complete: function () {
                                                        App.hideLoader();
                                                    },
                                                    success: function (data) {
                                                        if (data.error) {
                                                            App.notice(App.locale.error, 'error', data.error);
                                                        }
                                                        if (data.success) {
                                                            App.notice(App.locale.success, 'success', data.success, 1);
                                                            $('#tr_resolved').hide();
                                                            $('#support_reply_form').hide();
                                                        }
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                <?php } ?>
                                <div class="clearfix" style="padding-top:10px;"></div>
                                <?php
                                    if(!empty($ticket_replies)):
                                        $pos = 0;
                                        foreach($ticket_replies AS $replies):
                                            $pos++;
                                            $color = ($pos % 2) ? 'background: #ededed;' : 'background: #6D313D;border-top:1px solid #4B212B;';
                                            ?>
                                            <table class="ticket" style="width:100%;" cellspacing="0">
                                                <thead>
                                                <th style="<?php echo $color; ?>"><?php echo ($replies['sender'] == $this->session->userdata(['user' => 'username'])) ? 'Me' : $replies['sender']; ?></th>
                                                <th style="text-align:right;<?php echo $color; ?>"><?php echo $replies['time_between']; ?></th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td colspan="2"><?php echo $replies['reply']; ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="clearfix" style="padding-top:10px;"></div>
                                        <?php
                                        endforeach;
                                    endif;
                                    echo $css;
                                    echo $js;
                                ?>
                                <div class="clearfix" style="padding-top:10px;"></div>
                                <?php
                                    if(isset($errors)){
                                        echo '<div class="e_note">' . $errors . '</div>';
                                    }
                                    if(isset($success)){
                                        echo '<div class="s_note">' . $success . '</div>';
                                    }
                                ?>
                                <?php if($ticket_data['status'] != 1 && $ticket_data['status'] != 3 && $ticket_data['status'] != 4){ ?>
                                    <form method="post" action="" id="support_reply_form">
                                        <div style="text-align:center;"><textarea name="text" id="ticket_reply"></textarea>
                                        </div>
                                        <div style="text-align:center;width:100%;">
                                            <button type="submit" name="submit_reply"
                                                    class="button-style2"><?php echo __('Submit'); ?></button>
                                        </div>
                                    </form>
                                    <script>
                                        $(document).ready(function () {
                                            $("#ticket_reply").cleditor({
                                                width: "99%",
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
                                <?php } ?>
                            </div>
                        <?php
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>