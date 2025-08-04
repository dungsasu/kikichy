<div class="row gy-4">
    <div class="col-md-12">
        <div class="form-floating form-floating-outline">
            <input form="formAccountSettings" class="form-control" type="text" id="seo_title" name="info[seo_title]"
                value="{{ @$data->seo_title }}" placeholder=""/>
            <label for="firstName">SEO title</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-floating form-floating-outline">
            <input form="formAccountSettings" class="form-control" type="text" id="seo_keyword" name="info[seo_keyword]"
                value="{{ @$data->seo_keyword }}" placeholder="" />
            <label for="lastName">SEO Keyword</label>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-floating form-floating-outline">
            <input form="formAccountSettings" class="form-control" type="text" id="seo_description" name="info[seo_description]"
                value="{{ @$data->seo_description }}" placeholder="" />
            <label for="ordering">SEO Description</label>
        </div>
    </div>
</div>
