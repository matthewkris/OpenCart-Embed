<div class="oc-product">
    <div class="oc-product-content">
        <div class="oc-image"><a href = "/<?php echo $product->url; ?>"><img src = "/image/<?php echo $product->image; ?>" alt="<?php echo $product->name; ?>"></a></div>
        <div class="oc-name"><a href = "/<?php echo $product->url; ?>"><?php echo $product->name; ?></a></div>
        <div class="oc-price">$<?php echo money_format('%i', $product->price); ?></div>
    </div>
</div>