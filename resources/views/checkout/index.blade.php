@extends('layouts.app')

@section('title', 'Checkout - Premium Spices')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="mb-4">Checkout</h2>
            
            <!-- Shipping Address Section -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    @if($addresses->count() > 0)
                        <!-- Existing Addresses -->
                        <div class="mb-4">
                            <h6 class="mb-3">Select from saved addresses:</h6>
                            <div class="row">
                                @foreach($addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="border rounded p-3 address-card" style="cursor: pointer;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="address_id" 
                                                   id="address_{{ $address->id }}" value="{{ $address->id }}"
                                                   {{ $address->is_default ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="address_{{ $address->id }}">
                                                <strong>{{ $address->name }}</strong><br>
                                                {{ $address->address_line_1 }}<br>
                                                @if($address->address_line_2)
                                                    {{ $address->address_line_2 }}<br>
                                                @endif
                                                {{ $address->city }}, {{ $address->state }} {{ $address->pincode }}<br>
                                                {{ $address->country }}<br>
                                                Phone: {{ $address->phone }}
                                                @if($address->is_default)
                                                    <span class="badge bg-primary ms-2">Default</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-link p-0 mt-2" id="add-new-address">
                                + Add a new address
                            </button>
                        </div>
                    @endif

                    <!-- New Address Form -->
                    <div id="new-address-form" style="{{ $addresses->count() > 0 ? 'display: none;' : '' }}">
                        <form id="address-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address_line_1" class="form-label">Address Line 1 *</label>
                                <input type="text" class="form-control" id="address_line_1" name="address_line_1" 
                                       placeholder="House number, street name, etc." required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address_line_2" class="form-label">Address Line 2</label>
                                <input type="text" class="form-control" id="address_line_2" name="address_line_2" 
                                       placeholder="Apartment, suite, unit, etc. (optional)">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="state" class="form-label">State *</label>
                                    <select class="form-control" id="state" name="state" required>
                                        <option value="">Select State</option>
                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                        <option value="Assam">Assam</option>
                                        <option value="Bihar">Bihar</option>
                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                        <option value="Goa">Goa</option>
                                        <option value="Gujarat">Gujarat</option>
                                        <option value="Haryana">Haryana</option>
                                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                                        <option value="Jharkhand">Jharkhand</option>
                                        <option value="Karnataka">Karnataka</option>
                                        <option value="Kerala">Kerala</option>
                                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                                        <option value="Maharashtra">Maharashtra</option>
                                        <option value="Manipur">Manipur</option>
                                        <option value="Meghalaya">Meghalaya</option>
                                        <option value="Mizoram">Mizoram</option>
                                        <option value="Nagaland">Nagaland</option>
                                        <option value="Odisha">Odisha</option>
                                        <option value="Punjab">Punjab</option>
                                        <option value="Rajasthan">Rajasthan</option>
                                        <option value="Sikkim">Sikkim</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                        <option value="Telangana">Telangana</option>
                                        <option value="Tripura">Tripura</option>
                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                        <option value="Uttarakhand">Uttarakhand</option>
                                        <option value="West Bengal">West Bengal</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pincode" class="form-label">Pincode *</label>
                                    <input type="text" class="form-control" id="pincode" name="pincode" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" value="India" readonly>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="save_address" name="save_address" checked>
                                <label class="form-check-label" for="save_address">
                                    Save this address for future use
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                <label class="form-check-label" for="is_default">
                                    Set as default address
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Shipping Method -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Method</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping_method" id="standard_shipping" 
                               value="standard" checked>
                        <label class="form-check-label d-flex justify-content-between w-100" for="standard_shipping">
                            <span>
                                <strong>Standard Shipping</strong><br>
                                <small class="text-muted">Delivery in 5-7 business days</small>
                            </span>
                            <span class="text-success">FREE</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping_method" id="express_shipping" 
                               value="express">
                        <label class="form-check-label d-flex justify-content-between w-100" for="express_shipping">
                            <span>
                                <strong>Express Shipping</strong><br>
                                <small class="text-muted">Delivery in 2-3 business days</small>
                            </span>
                            <span>₹100</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="mb-0">{{ $item->product->name }}</p>
                            <small class="text-muted">
                                @if($item->variant)
                                    {{ $item->variant->weight }} × {{ $item->quantity }}
                                @else
                                    Qty: {{ $item->quantity }}
                                @endif
                            </small>
                        </div>
                        <span>₹{{ number_format($item->quantity * ($item->variant ? $item->variant->price : $item->product->price), 2) }}</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>GST (18%)</span>
                        <span>₹{{ number_format($gst, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span id="shipping-cost">FREE</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong id="total-amount">₹{{ number_format($total, 2) }}</strong>
                    </div>
                    
                    <button type="button" class="btn btn-success w-100" id="place-order-btn">
                        Pay with Razorpay
                    </button>
                    
                    <p class="text-center text-muted mt-3 mb-0">
                        <small>By placing this order, you agree to our terms and conditions</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addNewAddressBtn = document.getElementById('add-new-address');
    const newAddressForm = document.getElementById('new-address-form');
    const addressCards = document.querySelectorAll('.address-card');
    const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
    const shippingCostElement = document.getElementById('shipping-cost');
    const totalAmountElement = document.getElementById('total-amount');
    const placeOrderBtn = document.getElementById('place-order-btn');
    
    let baseTotal = {{ $total }};
    
    // Show new address form
    if (addNewAddressBtn) {
        addNewAddressBtn.addEventListener('click', function() {
            newAddressForm.style.display = 'block';
            this.style.display = 'none';
            // Uncheck all existing addresses
            document.querySelectorAll('input[name="address_id"]').forEach(radio => {
                radio.checked = false;
            });
        });
    }
    
    // Handle address card click
    addressCards.forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            // Hide new address form if shown
            if (newAddressForm && addNewAddressBtn) {
                newAddressForm.style.display = 'none';
                addNewAddressBtn.style.display = 'inline-block';
            }
        });
    });
    
    // Handle shipping method change
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'express') {
                shippingCostElement.textContent = '₹100';
                totalAmountElement.textContent = '₹' + (baseTotal + 100).toFixed(2);
            } else {
                shippingCostElement.textContent = 'FREE';
                totalAmountElement.textContent = '₹' + baseTotal.toFixed(2);
            }
        });
    });
    
    // Handle place order with Razorpay
    placeOrderBtn.addEventListener('click', async function() {
        // Validate address selection
        const selectedAddress = document.querySelector('input[name="address_id"]:checked');
        const isNewAddress = newAddressForm.style.display !== 'none';
        
        if (!selectedAddress && !isNewAddress) {
            alert('Please select or add a shipping address');
            return;
        }
        
        // Save address data to session first
        const addressData = {};
        
        if (isNewAddress) {
            // Validate new address form
            const form = document.getElementById('address-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Get form data
            const formData = new FormData(form);
            addressData.new_address = {
                name: formData.get('name'),
                phone: formData.get('phone'),
                address_line_1: formData.get('address_line_1'),
                address_line_2: formData.get('address_line_2'),
                city: formData.get('city'),
                state: formData.get('state'),
                country: formData.get('country'),
                pincode: formData.get('pincode'),
                save_address: formData.get('save_address') ? true : false,
                is_default: formData.get('is_default') ? true : false
            };
        } else {
            addressData.address_id = selectedAddress.value;
        }
        
        addressData.shipping_method = document.querySelector('input[name="shipping_method"]:checked').value;
        
        // Store address data in session
        try {
            const sessionResponse = await fetch('/checkout/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(addressData)
            });
            
            if (!sessionResponse.ok) {
                const errorData = await sessionResponse.json();
                alert(errorData.message || 'Failed to process checkout');
                return;
            }
        } catch (error) {
            console.error('Error saving address:', error);
            alert('An error occurred while processing your order');
            return;
        }
        
        // Create Razorpay order
        try {
            const response = await fetch('/razorpay/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const orderData = await response.json();
            
            if (orderData.error) {
                alert(orderData.error);
                return;
            }
            
            // Razorpay payment options
            const options = {
                key: orderData.key,
                amount: orderData.amount * 100,
                currency: orderData.currency,
                order_id: orderData.order_id,
                name: 'Flavearth',
                description: 'Premium Spices Order',
                handler: async function(response) {
                    // Verify payment
                    try {
                        const verifyResponse = await fetch('/razorpay/verify-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        });
                        
                        const verifyData = await verifyResponse.json();
                        
                        if (verifyData.success) {
                            // Redirect to confirmation page
                            window.location.href = verifyData.redirect_url;
                        } else {
                            alert('Payment verification failed');
                        }
                    } catch (error) {
                        console.error('Payment verification error:', error);
                        alert('An error occurred while verifying your payment');
                    }
                },
                prefill: {
                    name: '{{ auth()->user()->name }}',
                    email: '{{ auth()->user()->email }}',
                    contact: addressData.new_address ? addressData.new_address.phone : ''
                },
                theme: {
                    color: '#198754'
                },
                modal: {
                    ondismiss: function() {
                        console.log('Payment cancelled');
                    }
                }
            };
            
            // Open Razorpay checkout
            const rzp = new Razorpay(options);
            rzp.open();
            
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while creating your order');
        }
    });
});
</script>
@endsection