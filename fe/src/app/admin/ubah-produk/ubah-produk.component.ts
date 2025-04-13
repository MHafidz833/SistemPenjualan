import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';

@Component({
  selector: 'admin-app-ubah-produk',
  imports: [CommonModule, ReactiveFormsModule, SidebarComponent],
  templateUrl: './ubah-produk.component.html',
  styleUrl: './ubah-produk.component.css'
})
export class AdminUbahProdukComponent implements OnInit {
  productForm!: FormGroup;
  productId!: string;
  selectedImage: string | null = null;
  categories: any[] = []; // Data kategori

  private apiUrl = 'http://localhost/mahiahijab/api/admin/product/Product.php';
  private categoryApiUrl = 'http://localhost/mahiahijab/api/admin/product/Category.php';
  previewImage: string | ArrayBuffer | null = '';

  constructor(
    private route: ActivatedRoute,
    private http: HttpClient,
    private fb: FormBuilder,
    public router: Router
  ) { }

  ngOnInit(): void {
    this.productId = this.route.snapshot.paramMap.get('id') || '';

    this.productForm = this.fb.group({
      id_produk: [this.productId], // Menyimpan ID produk secara langsung
      nama: ['', Validators.required],
      id_kategori: ['', Validators.required],
      berat: ['', Validators.required],
      harga: ['', Validators.required],
      stok: ['', Validators.required],
      deskripsi: ['', Validators.required],
      img: this.fb.group({ name: '', data: '' })
    });

    this.getCategories();
    this.getProduct();
  }

  // Pastikan ID juga dimasukkan saat mengambil data produk
  getProduct() {
    this.http.get<any>(`${this.apiUrl}?id=${this.productId}`).subscribe(
      response => {
        if (response.status === 'success') {
          this.productForm.patchValue({ ...response.product, id: this.productId }); // Pastikan ID juga dimasukkan
          if (response.product.gambar) {
            this.selectedImage = `../../../assets/admin/assets/images/foto_produk/${response.product.gambar}`;
          }
        }
      },
      error => console.error('Gagal mengambil data produk:', error)
    );
  }


  // Mengambil daftar kategori dari API
  getCategories() {
    this.http.get<any[]>(this.categoryApiUrl).subscribe(
      response => {
        this.categories = response;
      },
      error => console.error('Gagal mengambil kategori:', error)
    );
  }

  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e: any) => {
        this.productForm.patchValue({
          img: { name: file.name, data: e.target.result.split(',')[1] } // Konversi Base64
        });
        this.previewImage = e.target.result; // Preview gambar
      };
      reader.readAsDataURL(file);
    }
  }
  

  // Menyimpan perubahan produk
  updateProduct() {
    console.log(this.productForm.value);
    if (this.productForm.valid) {
      this.http.put(`${this.apiUrl}`, this.productForm.value).subscribe(
        response => {
          console.log(response);
          alert('Produk berhasil diperbarui!');
          this.router.navigate(['/admin/product']);
        },
        error => console.error('Gagal mengupdate produk:', error)
      );
    }
  }
}