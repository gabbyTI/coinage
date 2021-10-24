<div class="row g-3 align-center">
    <div class="col-lg-5" {{ !empty($offer) ? 'hidden' : NULL }}>
        <div class="form-group">
            {{--<label class="form-label">Percentage</label>
            <span class="form-note">Specify the increase or decrease of the current crypto price</span>--}}
            <span class="form-note">(Current market value at <span id="crypto-current1"></span>)</span>
        </div>
    </div>
    <div class="col-lg-7" {{ !empty($offer) ? 'hidden' : NULL }}>
        <div class="form-group">
            <div class="custom-control custom-radio">
                <input class="custom-control-input crypto_type" type="radio" name="crypto_type" id="crypto-btc" value="BTC" {{ !empty($offer) ? ($offer->crypto_type === 'BTC' ? 'checked' : NULL) : 'checked' }}>
                <label class="custom-control-label" for="crypto-btc">BITCOIN</label>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input crypto_type" type="radio" name="crypto_type" id="crypto-eth" value="ETH" {{ !empty($offer) ? ($offer->crypto_type === 'ETH' ? 'checked' : NULL) : NULL }}>
                <label class="custom-control-label" for="crypto-eth">ETHEREUM</label>
            </div>
            {{-- <div class="custom-control custom-radio">
                <input class="custom-control-input crypto_type" type="radio" name="crypto_type" id="crypto-ltc" value="LTC" {{ !empty($offer) ? ($offer->crypto_type === 'LTC' ? 'checked' : NULL) : NULL }}>
                <label class="custom-control-label" for="crypto-ltc">LITECOIN</label>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input crypto_type" type="radio" name="crypto_type" id="crypto-xrp" value="XRP" {{ !empty($offer) ? ($offer->crypto_type === 'XRP' ? 'checked' : NULL) : NULL }}>
                <label class="custom-control-label" for="crypto-xrp">X-RIPPLE</label>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input crypto_type" type="radio" name="crypto_type" id="crypto-doge" value="DOGE" {{ !empty($offer) ? ($offer->crypto_type === 'DOGE' ? 'checked' : NULL) : NULL }}>
                <label class="custom-control-label" for="crypto-doge">DOGECOIN</label>
            </div> --}}

			<div class="custom-control custom-radio">
                <input class="custom-control-input crypto_type" type="radio" name="crypto_type" id="crypto-usdt" value="USDT" {{ !empty($offer) ? ($offer->crypto_type === 'USDT' ? 'checked' : NULL) : NULL }}>
                <label class="custom-control-label" for="crypto-usdt">USD TETHER</label>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 align-center">
    <div class="col-lg-5">
        <div class="form-group">
            <label for="percentage" class="form-label">Percentage</label>
            <span class="form-note">Specify the increase or decrease of the current crypto price</span>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="form-group">
            <div class="form-control-wrap">
                <input type="number" min="-10" max="10" class="form-control" id="percentage" name="percentage" value="{{ !empty($offer) ? $offer->percentage : 10 }}">
                <span class="form-note">(<span id="crypto-percent"></span>% of <span id="crypto-current"></span> <i class="icon ni ni-arrow-long-right"></i> <span id="crypto-total"></span>)</span>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 align-center">
    <div class="col-lg-5">
        <div class="form-group">
            <label for="min_amount" class="form-label">Minimum Amount</label>
            <span class="form-note">Minimum price your can sell (in NGN)</span>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="form-group">
            <div class="form-control-wrap">
                <input type="number" class="form-control" id="min_amount" name="min_amount" value="{{ !empty($offer) ? $offer->min_amount : 5000 }}">
            </div>
        </div>
    </div>
</div>
<div class="row g-3 align-center">
    <div class="col-lg-5">
        <div class="form-group">
            <label for="max_amount" class="form-label">Maximum Amount</label>
            <span class="form-note">Maximum price your can sell (in NGN)</span>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="form-group">
            <div class="form-control-wrap">
                <input type="number" class="form-control" id="max_amount" name="max_amount" value="{{ !empty($offer) ? $offer->max_amount : 100000 }}">
            </div>
        </div>
    </div>
</div>

@if(empty($offer))
    <div class="row g-3">
        <div class="col-lg-7 offset-lg-5">
            <div class="form-group mt-2">
                <button type="submit" class="btn btn-lg btn-primary">Create</button>
            </div>
        </div>
    </div>
@endif
