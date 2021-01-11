<?php
    in_file();

    class paypal_express extends library
    {
        private $api_username = "";
        private $api_password = "";
        private $api_signature = "";
        private $api_endpoint = "https://api-3t.sandbox.paypal.com/nvp";
        private $paypal_url = "https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=";
        private $paypal_debug = 0;
        private $sandbox = true;
        private $version = "93";

        public function __construct($credentials = [])
        {
            if(isset($credentials['version'])){
                $this->version = $credentials['version'];
            }
            if(isset($credentials['api_username'])){
                $this->api_username = $credentials['api_username'];
            }
            if(isset($credentials['api_password'])){
                $this->api_password = $credentials['api_password'];
            }
            if(isset($credentials['api_signature'])){
                $this->api_signature = $credentials['api_signature'];
            }
            if(isset($credentials['sandbox'])){
                $this->sandbox = (bool)$credentials['sandbox'];
                if($this->sandbox === false){
                    $this->api_endpoint = "https://api-3t.paypal.com/nvp";
                    $this->paypal_url = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
                }
            }
        }

        public function setExpressCheckout($ec_data = [], $custom_data = false)
        {
            $nvpstr = '';
            if($custom_data === false){
                if(isset($ec_data['currency'])){
                    $nvpstr .= "&PAYMENTREQUEST_0_CURRENCYCODE=" . urlencode($ec_data['currency']);
                }
                if(isset($ec_data['desc'])){
                    $nvpstr .= "&PAYMENTREQUEST_n_DESC=" . urlencode($ec_data['desc']);
                }
                if(isset($ec_data['type'])){
                    $nvpstr .= "&PAYMENTREQUEST_0_PAYMENTACTION=" . urlencode($ec_data['type']);
                } else{
                    $nvpstr .= "&PAYMENTREQUEST_0_PAYMENTACTION=Sale";
                }
                if(isset($ec_data['return_URL'])){
                    $nvpstr .= "&RETURNURL=" . urlencode($ec_data['return_URL']);
                }
                if(isset($ec_data['cancel_URL'])){
                    $nvpstr .= "&CANCELURL=" . urlencode($ec_data['cancel_URL']);
                }
                if(isset($ec_data['get_shipping'])){
                    $nvpstr .= ($ec_data['get_shipping'] === true) ? "&NOSHIPPING=2" : "&NOSHIPPING=1";
                } else{
                    $nvpstr .= "&NOSHIPPING=0";
                }
                $shipping_amount = 0;
                if(isset($ec_data['shipping_amount'])){
                    $shipping_amount = (float)$ec_data['shipping_amount'];
                    $nvpstr .= "&PAYMENTREQUEST_0_SHIPPINGAMT=" . urlencode(sprintf('%.2f', $shipping_amount));
                }
                $handling_amount = 0;
                if(isset($ec_data['handling_amount'])){
                    $handling_amount = (float)$ec_data['handling_amount'];
                    $nvpstr .= "&PAYMENTREQUEST_0_HANDLINGAMT=" . urlencode(sprintf('%.2f', $handling_amount));
                }
                $tax_amount = 0;
                if(isset($ec_data['tax_amount'])){
                    $tax_amount = (float)$ec_data['tax_amount'];
                    $nvpstr .= "&PAYMENTREQUEST_0_TAXAMT=" . urlencode(sprintf('%.2f', $tax_amount));
                }
                $total_amount = 0;
                foreach($ec_data['products'] as $k => $v){
                    if(isset($v['name'])){
                        $nvpstr .= "&L_PAYMENTREQUEST_0_NAME$k=" . urlencode($v['name']);
                    }
                    if(isset($v['desc'])){
                        $nvpstr .= "&L_PAYMENTREQUEST_0_DESC$k=" . urlencode($v['desc']);
                    }
                    if(isset($v['number'])){
                        $nvpstr .= "&L_PAYMENTREQUEST_0_NUMBER$k=" . urlencode($v['number']);
                    }
                    if(isset($v['quantity'])){
                        $nvpstr .= "&L_PAYMENTREQUEST_0_QTY$k=" . urlencode($v['quantity']);
                    }
                    if(isset($v['amount'])){
                        $nvpstr .= "&L_PAYMENTREQUEST_0_AMT$k=" . urlencode($v['amount']);
                        if(isset($v['quantity'])){
                            $total_amount += (float)($v['amount'] * (int)$v['quantity']);
                        } else{
                            $total_amount += (float)$v['amount'];
                        }
                    } else{
                        $nvpstr .= "&L_PAYMENTREQUEST_0_AMT$k=" . urlencode('0.00');
                        $total_amount += 0;
                    }
                }
                $nvpstr .= "&PAYMENTREQUEST_0_ITEMAMT=" . urlencode(sprintf('%.2f', $total_amount));
                $nvpstr .= "&PAYMENTREQUEST_0_AMT=" . urlencode(sprintf('%.2f', $total_amount + $shipping_amount + $tax_amount + $handling_amount));
            } else{
                foreach($ec_data as $k => $v){
                    $nvpstr .= "&$k=" . urlencode($v);
                }
            }
            $result = $this->callPaypal("SetExpressCheckout", $nvpstr);
            if(isset($result["ACK"])){
                $ack = strtoupper($result["ACK"]);
                if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING"){
                    $result['ec_status'] = true;
                } else{
                    $result['ec_status'] = false;
                }
                return $result;
            }
            return false;
        }

        public function getExpressCheckoutDetails($token)
        {
            $nvpstr = "&TOKEN=" . $token;
            $result = $this->callPaypal("GetExpressCheckoutDetails", $nvpstr);
            if(isset($result["ACK"])){
                $ack = strtoupper($result["ACK"]);
                if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING"){
                    $result['ec_status'] = true;
                } else{
                    $result['ec_status'] = false;
                }
                return $result;
            }
            return false;
        }

        public function doExpressCheckoutPayment($ec_details = ['token' => '', 'payer_id' => '', 'currency' => '', 'amount' => '', 'IPN_URL' => '', 'type' => 'Sale'])
        {
            $nvpstr = '';
            if(isset($ec_details['token'])){
                $nvpstr .= '&TOKEN=' . urlencode($ec_details['token']);
            }
            if(isset($ec_details['payer_id'])){
                $nvpstr .= '&PAYERID=' . urlencode($ec_details['payer_id']);
            }
            if(isset($ec_details['type'])){
                $nvpstr .= '&PAYMENTREQUEST_0_PAYMENTACTION=' . urlencode($ec_details['type']);
            }
            if(isset($ec_details['amount'])){
                $nvpstr .= '&PAYMENTREQUEST_0_AMT=' . urlencode($ec_details['amount']);
            }
            if(isset($ec_details['currency'])){
                $nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($ec_details['currency']);
            }
            if(isset($ec_details['IPN_URL'])){
                $nvpstr .= '&NOTIFYURL=' . urlencode($ec_details['IPN_URL']);
            }
            $nvpstr .= '&IPADDRESS=' . urlencode($_SERVER['SERVER_NAME']);
            $result = $this->callPaypal("DoExpressCheckoutPayment", $nvpstr);
            $ack = strtoupper($result["ACK"]);
            if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING"){
                $result['ec_status'] = true;
            } else{
                $result['ec_status'] = false;
            }
            return $result;
        }

        private function callPaypal($methodName, $nvpStr)
        {
            $nvpreq = "METHOD=" . urlencode($methodName);
            $nvpreq .= "&VERSION=" . urlencode($this->version);
            $nvpreq .= "&PWD=" . urlencode($this->api_password);
            $nvpreq .= "&USER=" . urlencode($this->api_username);
            $nvpreq .= "&SIGNATURE=" . urlencode($this->api_signature) . $nvpStr;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_URL, $this->api_endpoint);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
            if($this->paypal_debug == 1){
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
            }
            $response = curl_exec($ch);
            if(curl_errno($ch) != 0){
                //$this->session->register('curl_error_no', curl_errno($ch));
                $this->session->register('curl_error_msg', curl_error($ch));
                writelog('Can\'t connect to PayPal to validate CheckOut message:' . curl_error($ch), 'Paypal');
                return false;
            } else{
                if($this->paypal_debug == 1){
                    writelog('HTTP request of validation request:' . curl_getinfo($ch, CURLINFO_HEADER_OUT) . ' for IPN payload: ' . $nvpreq, 'Paypal');
                    writelog('HTTP response of validation request: ' . $response, 'Paypal');
                }
                $tokens = explode("\r\n\r\n", trim($response));
                $response = trim(end($tokens));
                $nvpResArray = $this->deformatNvp($response);
                $nvpReqArray = $this->deformatNvp($nvpreq);
                //$this->session->register('nvpReqArray', $nvpReqArray);
                curl_close($ch);
                return $nvpResArray;
            }
        }

        public function redirectToPaypal($token)
        {
            $this->load->lib('mobile');
            $payPalURL = ($this->mobile->isMobile() === true) ? str_replace("_express-checkout", "_express-checkout-mobile", $this->paypal_url) : $this->paypal_url;
            $payPalURL = $this->paypal_url . $token;
            header("Location: " . $payPalURL);
            exit;
        }

        private function deformatNvp($nvpstr)
        {
            $intial = 0;
            $nvpArray = [];
            while(strlen($nvpstr)){
                $keypos = strpos($nvpstr, '=');
                $valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);
                $keyval = substr($nvpstr, $intial, $keypos);
                $valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
                $nvpArray[urldecode($keyval)] = urldecode($valval);
                $nvpstr = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
            }
            return $nvpArray;
        }
    }