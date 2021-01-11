<?php
    in_file();

    class itemimage extends library
    {
        public function load($item_id, $cat, $level = 0, $tags = 1, $search_cat = false, $extensions = ['jpg', 'jpeg', 'png', 'gif'])
        {
            if($search_cat == true){
                $real_cat = $this->website->db('web')->query('SELECT item_cat FROM DmN_Shopp WHERE item_id = ' . $this->website->db('web')->sanitize_var($item_id) . ' AND original_item_cat = ' . $this->website->db('web')->sanitize_var($cat) . '')->fetch();
            } else{
                $real_cat = ['item_cat' => $cat];
            }
            $exists = false;
            foreach($extensions as $ext){
                $id = ($level > 0) ? $item_id . '-' . $level : $item_id;
                $img_with_lvl = BASEDIR . 'assets' . DS . 'item_images' . DS . $real_cat['item_cat'] . DS . $id . '.' . $ext;
                $img_no_lvl = BASEDIR . 'assets' . DS . 'item_images' . DS . $real_cat['item_cat'] . DS . $item_id . '.' . $ext;
                if($tags == 1){
                    if(file_exists($img_with_lvl)){
                        $exists = true;
                        list($width) = getimagesize($img_with_lvl);
                        $img = $this->config->base_url . 'assets/item_images/' . $real_cat['item_cat'] . '/' . $id . '.' . $ext;
                        $w = ($width >= 128) ? 'width:120px;' : '';
                        return '<img src="' . $img . '" alt="" style="border: 0px;' . $w . '"  />';
                    } else{
                        if(file_exists($img_no_lvl)){
                            $exists = true;
                            list($width) = getimagesize($img_no_lvl);
                            $img = $this->config->base_url . 'assets/item_images/' . $real_cat['item_cat'] . '/' . $item_id . '.' . $ext;
                            $w = ($width >= 128) ? 'width:120px;' : '';
                            return '<img src="' . $img . '" alt="" style="border: 0px;' . $w . '"  />';
                        }
                    }
                } else{
                    if(file_exists($img_with_lvl)){
                        $exists = true;
                        return $this->config->base_url . 'assets/item_images/' . $real_cat['item_cat'] . '/' . $id . '.' . $ext;
                    } else{
                        if(file_exists($img_no_lvl)){
                            $exists = true;
                            return $this->config->base_url . 'assets/item_images/' . $real_cat['item_cat'] . '/' . $item_id . '.' . $ext;
                        }
                    }
                }
            }
            if($exists == false){
                if($tags == 1){
                    return '<center><img src="' . $this->config->base_url . 'assets/item_images/no.png?' . $cat . '-' . $item_id . '" border="0" alt="" /></center>';
                } else{
                    return $this->config->base_url . 'assets/item_images/no.png?' . $cat . '-' . $item_id;
                }
            }
            return $this->config->base_url . 'assets/item_images/no.png?' . $cat . '-' . $item_id;
        }
    }