<div class="row gy-4">
    <div class="col-md-12">
        <div class="form-floating form-floating-outline">
            <input form="formAccountSettings" class="form-control" type="text" id="meta_title" name="meta_title"
                value="{{ @$data->meta_title }}" placeholder=""/>
            <label for="firstName">SEO title</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-floating form-floating-outline">
            <input form="formAccountSettings" class="form-control" type="text" name="meta_keyword" id="meta_keyword"
                value="{{ @$data->meta_keyword }}" placeholder="" />
            <label for="lastName">SEO Keyword</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-floating form-floating-outline">
            <input form="formAccountSettings" class="form-control" type="text" id="meta_description" name="meta_description"
                value="{{ @$data->meta_description }}" placeholder="" />
            <label for="ordering">SEO Description</label>
        </div>
    </div>
</div>
