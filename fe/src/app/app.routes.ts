import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CommonModule } from '@angular/common';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule, ReactiveFormsModule, FormBuilder, FormGroup, FormControl } from '@angular/forms';

import { NavbarComponent } from './navbar/navbar.component';
import { FooterComponent } from './footer/footer.component';
import { IndexComponent } from './index/index.component';

import { LoginComponent } from './login/login.component';

import { NotaOrderComponent } from './nota-order/nota-order.component';
import { BlogComponent } from './blog/blog.component';
import { SignupComponent } from './signup/signup.component';

import { DetailProdukComponent } from './detail-produk/detail-produk.component';

import { KonfirmasiPembayaranComponent } from './konfirmasi-pembayaran/konfirmasi-pembayaran.component';
import { OrderanComponent } from './orderan/orderan.component';
import { CheckoutComponent } from './checkout/checkout.component';
import { CartComponent } from './cart/cart.component';
import { DetailBlogComponent } from './detail-blog/detail-blog.component';
import { LupaPasswordComponent } from './lupa-password/lupa-password.component';
import { RincianProdukComponent } from './rincian-produk/rincian-produk.component';



import { AdminLoginComponent } from './admin/login/login.component';
import { AdminPelangganComponent } from './admin/pelanggan/pelanggan.component';
import { AdminProdukComponent } from './admin/produk/produk.component';

import { AdminAddProductComponent } from './admin/add-product/add-product.component';
import { DashboardComponent } from './admin/dashboard/dashboard.component'
import { AdminKategoriPosComponent } from './admin/kategori-pos/kategori-pos.component';
import { AdminTambahKategoriComponent } from './admin/tambah-kategori/tambah-kategori.component';
import { OrderComponent } from './admin/order/order.component';
import { PosComponent } from './admin/pos/pos.component';
import { AdminTambahPosComponent } from './admin/tambah-pos/tambah-pos.component';
import { AdminDetailOrderComponent } from './admin/detail-order/detail-order.component';
import { AdminPembayaranComponent } from './admin/pembayaran/pembayaran.component';
import { AdminEditOrderComponent } from './admin/edit-order/edit-order.component';
import { AdminUbahPosComponent } from './admin/ubah-pos/ubah-pos.component';
import { AdminUbahProdukComponent } from './admin/ubah-produk/ubah-produk.component';

export const routes: Routes = [
    { path: '', component: IndexComponent },

    { path: 'login', component: LoginComponent },
    { path: 'lupa-password', component: LupaPasswordComponent },

    { path: 'blog', component: BlogComponent },
    { path: 'signup', component: SignupComponent },
    { path: 'index', component: IndexComponent },
    { path: 'product/:id', component: DetailProdukComponent },
    { path: 'nota-order/:id', component: NotaOrderComponent },
    
    { path: 'konfirmasi-pembayaran/:id', component: KonfirmasiPembayaranComponent },
    { path: 'orderan', component: OrderanComponent },
    { path: 'checkout', component: CheckoutComponent },
    { path: 'cart', component: CartComponent },
    { path: 'blog/:id', component: DetailBlogComponent },
    { path: 'rincian-produk/:id', component: RincianProdukComponent },
    { path: 'pembayaran', component: RincianProdukComponent },

    { path: 'admin', component: DashboardComponent },
    { path: 'admin/login', component: AdminLoginComponent },
    { path: 'admin/pelanggan', component: AdminPelangganComponent },
    { path: 'admin/product', component: AdminProdukComponent },
    { path: 'admin/edit-product/:id', component: AdminUbahProdukComponent },
    { path: 'admin/add-product', component: AdminAddProductComponent },
    { path: 'admin/dashboard', component: DashboardComponent },
    { path: 'admin/category', component: AdminTambahKategoriComponent },
    { path: 'admin/kategori-pos', component: AdminKategoriPosComponent },
    { path: 'admin/order', component: OrderComponent },
    { path: 'admin/pos', component: PosComponent },
    { path: 'admin/edit-pos/:id', component: AdminUbahPosComponent },
    { path: 'admin/tambah-pos', component: AdminTambahPosComponent },
    { path: 'admin/order/:id', component: AdminDetailOrderComponent },
    { path: 'admin/pembayaran/:id', component: AdminPembayaranComponent },
    { path: 'admin/edit-order/:id', component: AdminEditOrderComponent },
];

@NgModule({
    imports: [RouterModule.forRoot(routes), CommonModule, BrowserModule],
    exports: [RouterModule]
})
export class AppRoutingModule { }