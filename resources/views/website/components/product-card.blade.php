@php
    // print_r($product['id']);
    $isNew = $product['created_at'] > now()->subDays(7);
    // $attachments = json_decode($product['attachments'], true);
    // print_r($attachments[0] ?? 'No Image');
@endphp

<div class="product product-2">
    <figure class="product-media">
        @if ($product['isHot'])
            <span class="product-label label-circle label-top">Hot</span>
        @endif
        @if ($isNew)
            <span class="product-label label-circle label-new">New</span>
        @endif
        @if (!empty($product->attachments))
            <img src="{{ asset('storage/' . $product->attachments[0]) }}" alt="Product Image" class="product-image"
                style="height: 300px; object-fit: cover;">
        @else
            <img src="{{ asset('storeAssets/images/placeholder.jpg') }}" alt="No Image" class="product-image"
                style="height: 300px; object-fit: cover;">
        @endif



        <div class="product-action-vertical">
            <a href="#" class="btn-product-icon btn-wishlist" title="Add to wishlist"></a>
        </div>
        <div class="product-action d-flex justify-content-around align-items-center">
            <a href="#signin-modal" class="btn-product-icon btn-cart trigger-login" data-toggle="modal"
                title="Add to cart">
                <i class="icon-shopping-bag"></i>
            </a>
            <a href="popup/quickView.php?id={{ $product['id'] }}" class="btn-product-icon btn-quickview mb-1"
                title="Quick view">
                <i class="icon-eye"></i>
            </a>
        </div>
    </figure>

    <div class="product-body">
        <div class="product-cat">
            <a href="category.html?id={{ $product['category_id'] }}">{{ $product['category_name'] }}</a>
        </div>
        <h3 class="product-title">
            <a href="product.html?id={{ $product['id'] }}">{{ $product['product_name'] }}</a>
        </h3>
        <div class="product-price">${{ number_format($product['price'], 2) }}</div>
    </div>
</div>
