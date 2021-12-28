<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    
    //mapped to table products
    protected $table = 'products';
    
    
    protected $fillable = [
        'brand_id', 'product_category_id', 'name', 'description',
        'price', 'old_price', 'index_page'
    ];
    
    
    //RELATIONSHIPS
    
    public function productCategory()
    {
        return $this->belongsTo(
            ProductCategory::class,
            'product_category_id', //preneseni kljuc u tabeli deteta
            'id' //naziv kljuca u tabeli roditelja
        );
    }
    
    public function brand()
    {
        //navedena je klasa sa kojom se vrsi relacija
        //ostale informacije ce laravel sam shvatiti,
        //ako je ispostovana konvencija
        return $this->belongsTo(Brand::class);
    }
    
    public function sizes()
    {
        return $this->belongsToMany(
            Size::class,
            'product_sizes',
            'product_id',
            'size_id'
        );
    }
    
    
    // QUERY SCOPES
    
    public function scopeNewArrivals($queryBuilder)
    {
        $queryBuilder->where('index_page', 1)
                ->where('created_at', '>=', date('Y-m-d', strtotime('-1 month')))
                ->orderBy('created_at', 'desc');
    }
    
    
    
    //HELPER FUNCTIONS
    
    /**
     * @return boolean
     */
    public function isOnIndexPage()
    {
        return $this->index_page == 1 ? true : false;
    }
    
    public function getPhoto1Url()
    {
		if ($this->photo1) {
			return url('/storage/products/' . $this->photo1);
		}
		
        return url('/themes/front/img/product-img/onsale-1.png');
    }
	
	public function getPhoto1ThumbUrl()
	{
		//originalna slika: /storage/products/11_photo1_blabla.png - 600x800
		//thumb slika		: /storage/products/thumbs/11_photo1_blabla.png  - 300x300
		
		if ($this->photo1) {
			return url('/storage/products/thumbs/' . $this->photo1);
		}
		
		return url('/themes/front/img/product-img/onsale-1.png');
	}
	
	public function deletePhoto1()
	{
		if (!$this->photo1) {
            //slika ne postoji u bazi
			return $this; //fluent interface
		}
		
        //brisanje originalne verzine
		$photoFilePath = public_path('/storage/products/' . $this->photo1);
		
		if (!is_file($photoFilePath)) {
			//informacija o fajlu postoji u bazi
			//ali fajl ne postoji fizicki na Hard Disku
			return $this;
		}
		unlink($photoFilePath);
		
		//brisanje thumb verzije
		$photoThumbPath = public_path('/storage/products/thumbs/' . $this->photo1);
		
		if (!is_file($photoThumbPath)) {
			//thumb slika ne postoji na disku
			return $this;
		}
		unlink($photoThumbPath);
		
		return $this;
	}
    
    public function getPhoto2Url()
    {
		if ($this->photo2) {
			return url('/storage/products/' . $this->photo2);
		}
		
        return url('/themes/front/img/product-img/best-1.png');
    }
	
	public function getPhoto2ThumbUrl()
	{
		//originalna slika: /storage/products/11_photo1_blabla.png - 600x800
		//thumb slika		: /storage/products/thumbs/11_photo1_blabla.png  - 300 x 300
		
		if ($this->photo2) {
			return url('/storage/products/thumbs/' . $this->photo2);
		}
		
		return url('/themes/front/img/product-img/onsale-1.png');
	}
	
	public function deletePhoto2()
	{
		if (!$this->photo2) {
			return $this; //fluent interface
		}
		
		$photoFilePath = public_path('/storage/products/' . $this->photo2);
		
		if (!is_file($photoFilePath)) {
			//informacija o fajlu postoji u bazi
			//ali fajl e postoji fizicki na Hard Disku
			return $this;
		}
		
		unlink($photoFilePath);
		
		//brisanje thumb verzije
		
		$photoThumbPath = public_path('/storage/products/thumbs/' . $this->photo2);
		
		if (!is_file($photoThumbPath)) {
			//thumb slika ne postoji na disku
			return $this;
		}
		
		unlink($photoThumbPath);
		
		return $this;
	}
	
	public function deletePhotos()
	{
		$this->deletePhoto1();
		$this->deletePhoto2();
		
		return $this;
	}
	
	public function deletePhoto($photoFieldName)
	{
		switch ($photoFieldName) {
			case 'photo1':
				$this->deletePhoto1();
				break;
			case 'photo2':
				$this->deletePhoto2();
				break;
		}
		
		return $this;
	}
	
	public function getPhotoUrl($photoFieldName)
	{
		switch ($photoFieldName) {
			case 'photo1':
				return $this->getPhoto1Url();
			case 'photo2':
				return $this->getPhoto2Url();
		}
		
		return url('/themes/front/img/product-img/onsale-1.png');
	}
    
    public function getFrontUrl()
    {
        return route('front.products.single', [
            'product' => $this->id,
        ]);
    }
}
