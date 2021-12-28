<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';
    
    protected $fillable = ['name', 'website'];
    
    public function products()
    {
        return $this->hasMany(
            Product::class,
            'brand_id',
            'id'
        );
    }
    
    public function getPhotoUrl()
    {
        if (!empty($this->photo)) {
            return url('/storage/brands/' . $this->photo);
        }
        
        return url('/themes/front/img/partner-img/5.jpg');
    }
    
    public function deletePhoto()
    {
        if (empty($this->photo)) {
            //nema slike
            
            return $this;
        }
        
        $photoFilePath = public_path('/storage/brands/' . $this->photo);
        
        if (!is_file($photoFilePath)) {
            //ne postoji fajl fizicki na disku
            return $this;
        }
        
        //brisanje fajla
        unlink($photoFilePath);
        
        return $this;
    }
}
