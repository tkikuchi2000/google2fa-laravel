# Setup T.B.D.

```bash
# composer.jsonにrepositoriesを追加
#    "repositories": {
#        "pragmarx/google2fa-laravel": {
#            "type": "vcs",
#            "url": "https://github.com/tkikuchi2000/google2fa-laravel.git"
#        }
#    }
$ composer config repositories.pragmarx/google2fa-laravel vcs https://github.com/tkikuchi2000/google2fa-laravel.git

# Google2FA for Laravelパッケージをインストール。
# * tagが存在する場合は、tagを指定
# * branch(ex. master)の場合、"dev-master"と指定する。
$ composer require pragmarx/google2fa-laravel:2.3.2

# 公開
$ php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider"

# QRコード生成パッケージ
$ composer require bacon/bacon-qr-code

# Recovery Codes 復旧コード生成 パッケージをインストール
$ composer require pragmarx/recovery
```

## ルート設定

```diff
-   Route::middleware(['auth', 'verified'])->group(function () {
+   Route::middleware(['auth', 'verified', '2fa'])->group(function () {
        Route::get('dashboard', function () {
            return Inertia::render('dashboard');
        })->name('dashboard');
        
+       Route::post('/2fa', fn() => redirect(route('dashboard')))->name('2fa');
    });
```

## View作成

`config/google2fa.php`編集. inertia.jsを利用するように修正

```diff
-   'type_view' => 'blade',
+   'type_view' => 'inertia',
```

* Inertia: `resources/js/pages/google2fa/index.tsx`
* Blade: `resources/google2fa/index.blade.php`



## User新規登録処理の修正

`app/Http/Controllers/Auth/RegisterdUserController.php`

```diff
    public function store(Request $request): RedirectResponse
    {
+       $google2fa = app('pragmarx.google2fa');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
+           'google2fa_secret' => $google2fa->generateSecretKey(),
        ]);

```

## Userモデル更新

```diff
  use Illuminate\Database\Eloquent\Casts\Attribute;
  
  class User extends Authenticatable
  {
      protected $fillable = [
+          'google2fa_secret'
      ];

      protected $hidden = [
+          'google2fa_secret'
      ];

+      protected function google2faSecret(): Attribute
+      {
+         return new Attribute(
+             get: fn($value) => decrypt($value),
+             set: fn($value) => encrypt($value),
+         ) ;
+      }
  }
```

