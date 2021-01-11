<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>gmcp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>gmcp/credits-adder">Credits Adder</a></li>
        </ul>
    </div>
    <?php
        if(isset($not_allowed)){
        echo '<div class="alert alert-error span9">' . $not_allowed . '</div>';
    }
        else{
        if(isset($error)){
            echo '<div class="alert alert-error span9">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span9">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Add Credits</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend>Add credits to character.</legend>
                        <div class="control-group">
                            <label class="control-label" for="name">Character </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="name" name="name"
                                       value="<?php if(isset($_POST['name'])){
                                           echo $_POST['name'];
                                       } ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="c_type">Credit Type </label>

                            <div class="controls">
                                <select id="c_type" name="c_type">
                                    <option value="">Select</option>
                                    <option value="1">Credits 1</option>
                                    <option value="2">Credits 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="amount">Amount </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="amount" name="amount"
                                       value="<?php if(isset($_POST['amount'])){
                                           echo $_POST['amount'];
                                       } ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="limit">Today Limit </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="limit" name="limit"
                                       value="<?php echo $credits_limit; ?>" readonly/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="add_credits">Add Credits</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <?php
            }
        ?>
    </div>
</div>