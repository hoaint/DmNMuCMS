<?php
    in_file();

    class webshop
    {
        protected $registry, $config, $load;
        public $errors = [];

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
        }

        public function load_cat_list($select = false, $cat = '', $only_original = false, $style = '', $load_all = false)
        {
            $load_cat_list = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_cat_list.dmn', FILE_SKIP_EMPTY_LINES);
            $list = '';
            $i = 0;
            foreach($load_cat_list as $key => $category){
                $category_data = explode('|', $category);
                if($only_original == true){
                    if($i > 16)
                        break;
                }
                if($category_data[3] == 0 && $load_all == false){
                    unset($load_cat_list[$key]);
                }
                if($select == true){
                    $list .= '<option value="' . $category_data[0] . '"';
                    if((isset($_POST['item_cat']) && $_POST['item_cat'] == $category_data[0]) || ($cat != '' && $cat == $category_data[0])){
                        $list .= 'selected="selected"';
                    }
                    $list .= '>' . $category_data[1] . '</option>' . "\n";
                } else{
                    if($category_data[3] == 1){
                        $list .= '<a ' . $style . ' href="' . $this->config->base_url . 'shop/category/' . $category_data[2] . '">' . $category_data[1] . '</a> - ';
                    }
                }
                $i++;
            }
            return ($select != true) ? substr($list, 0, -2) : $list;
        }

        public function load_cat_list_input()
        {
            $load_cat_list = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_cat_list.dmn', FILE_SKIP_EMPTY_LINES);
            $list = '';
            foreach($load_cat_list as $cat_list){
                $cats = explode('|', $cat_list);
                $list .= '<span>' . $cats[1] . ' <input type="checkbox" name="cat[]" value="' . $cats[0] . '" /></span>';
            }
            return $list;
        }

        public function load_cat_list_table($checked = false)
        {
            $load_cat_list = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_cat_list.dmn', FILE_SKIP_EMPTY_LINES);
            $list = '';
            $i = 0;
            foreach($load_cat_list as $cat_list){
                $cats = explode('|', $cat_list);
                $i++;
                /*if($i == 1){
                    $list .= '<tr style="text-align:center;">';
                }*/
                //$list .= '<td style="text-align:right;">'.$cats[1].' <input type="checkbox" name="cat[]" value="'.$cats[0].'" /></td>';
                if($checked !== false){
                    $cc = '';
                    foreach($checked as $c_cat){
                        if($c_cat == $cats[0]){
                            $cc = 'checked="checked"';
                        }
                    }
                    $list .= '<input type="checkbox" name="cat[]" value="' . $cats[0] . '" ' . $cc . '/> ' . $cats[1] . '<br />' . "\n";
                } else{
                    $list .= '<input type="checkbox" name="cat[]" value="' . $cats[0] . '" /> ' . $cats[1] . '<br />' . "\n";
                }
                /*if($i == 4){
                    $i = 0;
                    $list .= '</tr>';
                }*/
            }
            //$list .= '</table>';
            return $list;
        }

        public function category_to_id($name)
        {
            $load_cat_list = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_cat_list.dmn', FILE_SKIP_EMPTY_LINES);
            foreach($load_cat_list as $key => $category){
                $cat = explode('|', $category);
                if($cat[2] == htmlspecialchars($name)){
                    return $cat[0];
                }
            }
            return false;
        }

        public function category_to_name($name)
        {
            $load_cat_list = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_cat_list.dmn', FILE_SKIP_EMPTY_LINES);
            foreach($load_cat_list as $key => $category){
                $cat = explode('|', $category);
                if($cat[2] == htmlspecialchars($name)){
                    return ucfirst($cat[1]);
                }
            }
            return false;
        }

        public function category_from_id($id)
        {
            $load_cat_list = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_cat_list.dmn', FILE_SKIP_EMPTY_LINES);
            foreach($load_cat_list as $key => $category){
                $cat = explode('|', $category);
                if($cat[0] == $id){
                    return $cat[1];
                }
            }
            return false;
        }

        public function load_ancient_settings()
        {
            return file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_anc_opt.dmn', FILE_SKIP_EMPTY_LINES);
        }
    }