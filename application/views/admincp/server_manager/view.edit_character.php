<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/character-manager">Character Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Edit Character: <?php if($character_data != false){
                        echo $character_data['Name'];
                    }; ?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($error)){
                        if(is_array($error)){
                            foreach($error AS $err){
                                echo '<div class="alert alert-error">' . $err . '</div>';
                            }
                        } else{
                            echo '<div class="alert alert-error">' . $error . '</div>';
                        }
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                    if($character_data == false){
                        echo '<div class="alert alert-error">Character not found.</div>';
                    } else{
                        ?>
                        <form class="form-horizontal" method="post" action="">
                            <fieldset>
                                <legend></legend>
                                <div class="control-group">
                                    <label class="control-label" for="cLevel">Level </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="cLevel" id="cLevel"
                                               value="<?php echo $character_data['cLevel']; ?>"/>

                                        <p>Character Level</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="LevelUpPoint">Points </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="LevelUpPoint" id="LevelUpPoint"
                                               value="<?php echo $character_data['LevelUpPoint']; ?>"/>

                                        <p>Character LevelUp Points</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Class">Class </label>

                                    <div class="controls">
                                        <select name="Class" id="Class">
                                            <?php
                                                $classes = $this->website->get_char_class($character_data['Class'], false, true);
                                                foreach($classes as $key => $class):
                                                    ?>
                                                    <option
                                                            value="<?php echo $key; ?>" <?php if($character_data['Class'] == $key){
                                                        echo 'selected="selected"';
                                                    } ?>><?php echo $class['long']; ?> (<?php echo $key; ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                        </select>

                                        <p>Character Class</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Experience">Experience </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="Experience" id="Experience"
                                               value="<?php echo $character_data['Experience']; ?>"/>

                                        <p>Character Experience</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Strength">Strength </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="Strength" id="Strength"
                                               value="<?php echo $this->website->show65kStats($character_data['Strength']); ?>"/>

                                        <p>Character Strength</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Dexterity">Agility </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="Dexterity" id="Dexterity"
                                               value="<?php echo $this->website->show65kStats($character_data['Dexterity']); ?>"/>

                                        <p>Character Dexterity</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Energy">Energy </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="Energy" id="Energy"
                                               value="<?php echo $this->website->show65kStats($character_data['Energy']); ?>"/>

                                        <p>Character Energy</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Vitality">Vitality </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="Vitality" id="Vitality"
                                               value="<?php echo $this->website->show65kStats($character_data['Vitality']); ?>"/>

                                        <p>Character Vitality</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Leadership">Command </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="Leadership" id="Leadership"
                                               value="<?php echo $this->website->show65kStats($character_data['Leadership']); ?>" <?php if(!in_array($character_data['Class'], [64, 65, 66])){
                                            echo 'disabled="disabled"';
                                        } ?> />

                                        <p>Character Command</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="Money">Zen </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="Money" id="Money"
                                               value="<?php echo $character_data['Money']; ?>"/>

                                        <p>Character Zen</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="MapNumber">Location </label>

                                    <div class="controls">
                                        <select name="MapNumber" id="MapNumber">
                                            <?php
                                                $map = $this->website->get_map_name($character_data['MapNumber'], true);
                                                foreach($map as $key => $loc):
                                                    ?>
                                                    <option
                                                            value="<?php echo $key; ?>" <?php if($character_data['MapNumber'] == $key){
                                                        echo 'selected="selected"';
                                                    } ?>><?php echo $loc; ?></option>
                                                <?php endforeach; ?>
                                        </select> X <input type="text" class="input-mini" name="MapPosX" id="MapPosX"
                                                           value="<?php echo $character_data['MapPosX']; ?>"/> Y
                                        <input type="text" class="input-mini" name="MapPosY" id="MapPosY"
                                               value="<?php echo $character_data['MapPosY']; ?>"/>

                                        <p>Character Location</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="PkCount">PK Count </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="PkCount" id="PkCount"
                                               value="<?php echo $character_data['PkCount']; ?>"/>

                                        <p>Character PK Count</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="PkLevel">PK Level </label>

                                    <div class="controls">
                                        <select name="PkLevel" id="PkLevel">
                                            <?php
                                                $pklevel = $this->website->pk_level($character_data['PkLevel'], true);
                                                foreach($pklevel as $key => $lvl):
                                                    ?>
                                                    <option
                                                            value="<?php echo $key; ?>" <?php if($character_data['PkLevel'] == $key){
                                                        echo 'selected="selected"';
                                                    } ?>><?php echo $lvl; ?></option>
                                                <?php endforeach; ?>
                                        </select>

                                        <p>Character PK Level</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="PkTime">PK Time </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="PkTime" id="PkTime"
                                               value="<?php echo $character_data['PkTime']; ?>"/>

                                        <p>Character PK Time</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="CtlCode">Ctl Code </label>

                                    <div class="controls">
                                        <select name="CtlCode" id="CtlCode">
                                            <option value="0" <?php if($character_data['CtlCode'] == 0){
                                                echo 'selected="selected"';
                                            } ?>>Normal
                                            </option>
                                            <option value="1" <?php if($character_data['CtlCode'] == 1){
                                                echo 'selected="selected"';
                                            } ?>>Banned
                                            </option>
                                            <option value="32" <?php if($character_data['CtlCode'] == 32){
                                                echo 'selected="selected"';
                                            } ?>>GameMaster
                                            </option>
                                        </select>

                                        <p>Character Ctl Code</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="resets">Resets </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="resets" id="resets"
                                               value="<?php echo $character_data['resets']; ?>"/>

                                        <p>Character Resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="grand_resets">Grand Resets </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="grand_resets" id="grand_resets"
                                               value="<?php echo $character_data['grand_resets']; ?>"/>

                                        <p>Character Grand Reset</p>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="edit_character" class="btn btn-primary">Edit</button>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Other Account Characters</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Character</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(!empty($char_list)){
                            foreach($char_list as $key => $value){
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($value['Name']); ?></td>
                                    <td class="center">
                                        <a class="btn btn-success"
                                           href="<?php echo $this->config->base_url; ?>admincp/edit-character/<?php echo htmlspecialchars($value['id']); ?>">
                                            <i class="icon-edit icon-white"></i> Edit Character </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else{
                            echo '<tr><td colspan="3"><div class="alert alert-info">No characters found</div></td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Account IP Log</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>IP</th>
                        <th>Last Connected</th>
                        <th>Login Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(!empty($ip_logs)){
                            foreach($ip_logs as $key => $value){
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($value['ip']); ?></td>
                                    <td><?php echo htmlspecialchars($value['last_connected']); ?></td>
                                    <td><?php echo ($value['login_type'] == 1) ? 'Website' : 'Server'; ?></td>
                                    <td class="center">
                                        <a class="btn btn-success"
                                           href="<?php echo $this->config->base_url; ?>admincp/search-ip/<?php echo htmlspecialchars($value['ip']); ?>">
                                            <i class="icon-edit icon-white"></i> Search IP </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else{
                            echo '<tr><td colspan="4"><div class="alert alert-info">No ip logs found</div></td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>