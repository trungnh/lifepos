<?php
class Sale_lib
{
	var $CI;

  	function __construct()
	{
		$this->CI =& get_instance();
	}

	function get_cart()
	{
		if($this->CI->session->userdata('cart') === false)
			$this->set_cart(array());

		return $this->CI->session->userdata('cart');
	}

	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cart',$cart_data);
	}

	//Alain Multiple Payments
	function get_payments()
	{
		if($this->CI->session->userdata('payments') === false)
			$this->set_payments(array());

		return $this->CI->session->userdata('payments');
	}

	//Alain Multiple Payments
	function set_payments($payments_data)
	{
		$this->CI->session->set_userdata('payments',$payments_data);
	}
	
	function get_comment() 
	{
		return $this->CI->session->userdata('comment') ? $this->CI->session->userdata('comment') : '';
	}

	function set_comment($comment) 
	{
		$this->CI->session->set_userdata('comment', $comment);
	}

	function clear_comment() 	
	{
		$this->CI->session->unset_userdata('comment');
	}
	
	function get_email_receipt() 
	{
		return $this->CI->session->userdata('email_receipt');
	}

	function set_email_receipt($email_receipt) 
	{
		$this->CI->session->set_userdata('email_receipt', $email_receipt);
	}

	function clear_email_receipt() 	
	{
		$this->CI->session->unset_userdata('email_receipt');
	}

	function add_payment($payment_id,$payment_amount)
	{
		$payments=$this->get_payments();
		$payment = array($payment_id=>
		array(
			'payment_type'=>$payment_id,
			'payment_amount'=>$payment_amount
			)
		);

		//payment_method already exists, add to payment_amount
		if(isset($payments[$payment_id]))
		{
			$payments[$payment_id]['payment_amount']+=$payment_amount;
		}
		else
		{
			//add to existing array
			$payments+=$payment;
		}

		$this->set_payments($payments);
		return true;

	}

	//Alain Multiple Payments
	function edit_payment($payment_id,$payment_amount)
	{
		$payments = $this->get_payments();
		if(isset($payments[$payment_id]))
		{
			$payments[$payment_id]['payment_type'] = $payment_id;
			$payments[$payment_id]['payment_amount'] = $payment_amount;
			$this->set_payments($payment_id);
		}

		return false;
	}

	//Alain Multiple Payments
	function delete_payment($payment_id)
	{
		$payments=$this->get_payments();
		unset($payments[$payment_id]);
		$this->set_payments($payments);
	}

	//Alain Multiple Payments
	function empty_payments()
	{
		$this->CI->session->unset_userdata('payments');
	}

	//Alain Multiple Payments
	function get_payments_total()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{
		    $subtotal+=$payments['payment_amount'];
		}
		return to_currency_no_money($subtotal);
	}

	//Alain Multiple Payments
	function get_amount_due($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->get_payments_total();
		$sales_total=$this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_customer()
	{
		if(!$this->CI->session->userdata('customer'))
			$this->set_customer(-1);

		return $this->CI->session->userdata('customer');
	}

	function set_customer($customer_id)
	{
		$this->CI->session->set_userdata('customer',$customer_id);
	}

	function get_mode()
	{
		if(!$this->CI->session->userdata('sale_mode'))
			$this->set_mode('sale');

		return $this->CI->session->userdata('sale_mode');
	}

	function set_mode($mode)
	{
		$this->CI->session->set_userdata('sale_mode',$mode);
	}

	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null)
	{
		//make sure item exists
		if(!$this->CI->Item->exists(is_numeric($item_id) ? (int)$item_id : -1))	
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		else
		{
			$item_id = (int)$item_id;
		}
		//Alain Serialization and Description

		//Get all items in the cart so far...
		$items = $this->get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
            //Also, we have stored the key in the element itself so we can compare.

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}

			if(isset($item['item_id']) && $item['item_id']==$item_id)
			{
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
			}
		}

		$insertkey=$maxkey+1;

		//array/cart records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey)=>
		array(
			'item_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$this->CI->Item->get_info($item_id)->name,
			'item_number'=>$this->CI->Item->get_info($item_id)->item_number,
			'description'=>$description!=null ? $description: $this->CI->Item->get_info($item_id)->description,
			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
			'allow_alt_description'=>$this->CI->Item->get_info($item_id)->allow_alt_description,
			'is_serialized'=>$this->CI->Item->get_info($item_id)->is_serialized,
			'quantity'=>$quantity,
            'discount'=>$discount,
			'price'=>$price!=null ? $price: $this->CI->Item->get_info($item_id)->unit_price
			)
		);

		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($this->CI->Item->get_info($item_id)->is_serialized ==0) )
		{
			$items[$updatekey]['quantity']+=$quantity;
		}
		else
		{
			//add to existing array
			$items+=$item;
		}

		$this->set_cart($items);
		return true;

	}
	
	function add_item_kit($external_item_kit_id_or_item_number,$quantity=1,$discount=0,$price=null,$description=null)
	{
		if (strpos($external_item_kit_id_or_item_number, 'KIT') !== FALSE)
		{
			//KIT #
			$pieces = explode(' ',$external_item_kit_id_or_item_number);
			$item_kit_id = (int)$pieces[1];	
		}
		else
		{
			$item_kit_id = $this->CI->Item_kit->get_item_kit_id($external_item_kit_id_or_item_number);
		}
		
		//make sure item exists
		if(!$this->CI->Item_kit->exists($item_kit_id))	
		{
			return false;
		}
		
		if ( $this->CI->Item_kit->get_info($item_kit_id)->unit_price == null)
		{
			foreach ($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
			{
				for($k=0;$k<$item_kit_item->quantity;$k++)
				{
					$this->add_item($item_kit_item->item_id, 1);
				}
			}
			
			return true;
		}
		else
		{
			$items = $this->get_cart();

	        //We need to loop through all items in the cart.
	        //If the item is already there, get it's key($updatekey).
	        //We also need to get the next key that we are going to use in case we need to add the
	        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

	        $maxkey=0;                       //Highest key so far
	        $itemalreadyinsale=FALSE;        //We did not find the item yet.
			$insertkey=0;                    //Key to use for new entry.
			$updatekey=0;                    //Key to use to update(quantity)

			foreach ($items as $item)
			{
	            //We primed the loop so maxkey is 0 the first time.
	            //Also, we have stored the key in the element itself so we can compare.

				if($maxkey <= $item['line'])
				{
					$maxkey = $item['line'];
				}

				if(isset($item['item_kit_id']) && $item['item_kit_id']==$item_kit_id)
				{
					$itemalreadyinsale=TRUE;
					$updatekey=$item['line'];
				}
			}

			$insertkey=$maxkey+1;

			//array/cart records are identified by $insertkey and item_id is just another field.
			$item = array(($insertkey)=>
			array(
				'item_kit_id'=>$item_kit_id,
				'line'=>$insertkey,
				'item_kit_number'=>$this->CI->Item_kit->get_info($item_kit_id)->item_kit_number,
				'name'=>$this->CI->Item_kit->get_info($item_kit_id)->name,
				'description'=>$description!=null ? $description: $this->CI->Item_kit->get_info($item_kit_id)->description,
				'quantity'=>$quantity,
	            'discount'=>$discount,
				'price'=>$price!=null ? $price: $this->CI->Item_kit->get_info($item_kit_id)->unit_price
				)
			);

			//Item already exists and is not serialized, add to quantity
			if($itemalreadyinsale)
			{
				$items[$updatekey]['quantity']+=$quantity;
			}
			else
			{
				//add to existing array
				$items+=$item;
			}

			$this->set_cart($items);
			return true;
		}
	}
	
	function out_of_stock($item_id)
	{
		//make sure item exists
		if(!$this->CI->Item->exists($item_id))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		
		$item = $this->CI->Item->get_info($item_id);
		$quanity_added = $this->get_quantity_already_added($item_id);
		
		if ($item->quantity - $quanity_added < 0)
		{
			return true;
		}
		
		return false;
	}
	
	function get_quantity_already_added($item_id)
	{
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item)
		{
			if(isset($item['item_id']) && $item['item_id']==$item_id)
			{
				$quanity_already_added+=$item['quantity'];
			}
		}
		
		return $quanity_already_added;
	}
	
	function get_item_id($line_to_get)
	{
		$items = $this->get_cart();

		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				return isset($item['item_id']) ? $item['item_id'] : -1;
			}
		}
		
		return -1;
	}

	function edit_item($line,$description,$serialnumber,$quantity,$discount,$price)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			$items[$line]['description'] = $description;
			$items[$line]['serialnumber'] = $serialnumber;
			$items[$line]['quantity'] = $quantity;
			$items[$line]['discount'] = $discount;
			$items[$line]['price'] = $price;
			$this->set_cart($items);
		}

		return false;
	}

	function is_valid_receipt($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);

		if(count($pieces)==2 && $pieces[0] == 'POS')
		{
			return $this->CI->Sale->exists($pieces[1]);
		}

		return false;
	}
	
	function is_valid_item_kit($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && $pieces[0] == 'KIT')
		{
			return $this->CI->Item_kit->exists($pieces[1]);
		}
		else
		{
			return $this->CI->Item_kit->get_item_kit_id($item_kit_id) !== FALSE;
		}
	}

	function return_entire_sale($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);
		$sale_id = $pieces[1];

		$this->empty_cart();
		$this->delete_customer();

		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id,-$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			$this->add_item_kit('KIT '.$row->item_kit_id,-$row->quantity_purchased,$row->discount_percent,$row->item_kit_unit_price,$row->description);
		}
		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);
	}
	
	function copy_entire_sale($sale_id)
	{
		$this->empty_cart();
		$this->delete_customer();

		foreach($this->CI->Sale->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			$this->add_item_kit('KIT '.$row->item_kit_id,$row->quantity_purchased,$row->discount_percent,$row->item_kit_unit_price,$row->description);
		}
		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount);
		}
		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);

	}
	
	function copy_entire_suspended_sale($sale_id)
	{
		$this->empty_cart();
		$this->delete_customer();

		foreach($this->CI->Sale_suspended->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}

		foreach($this->CI->Sale_suspended->get_sale_item_kits($sale_id)->result() as $row)
		{
			$this->add_item_kit('KIT '.$row->item_kit_id,$row->quantity_purchased,$row->discount_percent,$row->item_kit_unit_price,$row->description);
		}
		
		foreach($this->CI->Sale_suspended->get_sale_payments($sale_id)->result() as $row)
		{
			$this->add_payment($row->payment_type,$row->payment_amount);
		}
		$this->set_customer($this->CI->Sale_suspended->get_customer($sale_id)->person_id);
		$this->set_comment($this->CI->Sale_suspended->get_comment($sale_id));
	}
	
	function get_suspended_sale_id()
	{
		return $this->CI->session->userdata('suspended_sale_id');
	}
	
	function set_suspended_sale_id($suspended_sale_id)
	{
		$this->CI->session->set_userdata('suspended_sale_id',$suspended_sale_id);
	}
	
	function delete_suspended_sale_id()
	{
		$this->CI->session->unset_userdata('suspended_sale_id');
	}
	function delete_item($line)
	{
		$items=$this->get_cart();
		unset($items[$line]);
		$this->set_cart($items);
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cart');
	}

	function delete_customer()
	{
		$this->CI->session->unset_userdata('customer');
	}

	function clear_mode()
	{
		$this->CI->session->unset_userdata('sale_mode');
	}

	function clear_all()
	{
		$this->clear_mode();
		$this->empty_cart();
		$this->clear_comment();
		$this->clear_email_receipt();
		$this->empty_payments();
		$this->delete_customer();
		$this->delete_suspended_sale_id();
	}

	function get_taxes($sale_id = false)
	{
		$taxes = array();
		
		if ($sale_id)
		{
			$taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
			
			foreach($taxes_from_sale as $key=>$tax_item)
			{
				$name = $tax_item['percent'].'% ' . $tax_item['name'];
			
				if ($tax_item['cumulative'])
				{
					$prev_tax = $taxes[$taxes_from_sale[$key-1]['percent'].'% ' . $taxes_from_sale[$key-1]['name']];
					$tax_amount=(($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100) + $prev_tax)*(($tax_item['percent'])/100);					
				}
				else
				{
					$tax_amount=($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
				}

				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;
			}
		}
		else
		{
			$customer_id = $this->get_customer();
			$customer = $this->CI->Customer->get_info($customer_id);

			//Do not charge sales tax if we have a customer that is not taxable
			if (!$customer->taxable and $customer_id!=-1)
			{
			   return array();
			}

			foreach($this->get_cart() as $line=>$item)
			{
				$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes->get_info($item['item_id']) : $this->CI->Item_kit_taxes->get_info($item['item_kit_id']);
				foreach($tax_info as $key=>$tax)
				{
					$name = $tax['percent'].'% ' . $tax['name'];
				
					if ($tax['cumulative'])
					{
						$prev_tax = $taxes[$tax_info[$key-1]['percent'].'% ' . $tax_info[$key-1]['name']];
						$tax_amount=(($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);					
					}
					else
					{
						$tax_amount=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					}

					if (!isset($taxes[$name]))
					{
						$taxes[$name] = 0;
					}
					$taxes[$name] += $tax_amount;
				}
			}
		}
		
		return $taxes;
	}
	
	function get_items_in_cart()
	{
		$items_in_cart = 0;
		foreach($this->get_cart() as $item)
		{
		    $items_in_cart+=$item['quantity'];
		}
		
		return $items_in_cart;
	}
	
	function get_subtotal()
	{
		$subtotal = 0;
		foreach($this->get_cart() as $item)
		{
		    $subtotal+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);
		}
		return to_currency_no_money($subtotal);
	}

	function get_total($sale_id = false)
	{
		$total = 0;
		foreach($this->get_cart() as $item)
		{
            $total+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);
		}

		foreach($this->get_taxes($sale_id) as $tax)
		{
			$total+=$tax;
		}

		return to_currency_no_money($total);
	}
}
?>