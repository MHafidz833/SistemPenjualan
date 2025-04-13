import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';

@Component({
  selector: 'admin-app-add-product',
  templateUrl: './add-product.component.html',
  styleUrls: ['./add-product.component.css'],
  imports: [CommonModule, FormsModule, ReactiveFormsModule, SidebarComponent]
})
export class AdminAddProductComponent implements OnInit {
  productForm: FormGroup;
  previewImage: string | ArrayBuffer | null = '';
  apiUrl = 'http://localhost/mahiahijab/api/admin/product/Product.php';
  categoryApiUrl = 'http://localhost/mahiahijab/api/admin/product/Category.php';
  categories: any[] = []; // Array untuk menyimpan kategori

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) {
    this.productForm = this.fb.group({
      id_kategori: ['', Validators.required],
      nama: ['', Validators.required],
      berat: ['', Validators.required],
      harga: ['', Validators.required],
      stok: ['', Validators.required],
      deskripsi: ['', Validators.required],
      img: this.fb.group({ name: '', data: '' }) // Data gambar dalam Base64
    });
  }

  ngOnInit() {
    this.fetchCategories(); // Panggil fungsi untuk mengambil kategori saat komponen di-load
  }

  // Ambil kategori dari API
  fetchCategories() {
    this.http.get<any[]>(this.categoryApiUrl).subscribe(
      (response) => {
        this.categories = response.map(item => ({
          id: item.id_kategori,
          name: item.nm_kategori
        }));
        console.log('Kategori:', this.categories);
      },
      (error) => {
        console.error('Gagal mengambil kategori:', error);
      }
    );
  }

  // Konversi gambar ke Base64
  onFileSelected(event: any) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e: any) => {
        const base64Image = e.target.result.split(',')[1]; // Ambil data Base64
        this.productForm.patchValue({
          img: { name: file.name, data: base64Image }
        });
        this.previewImage = e.target.result; // Preview
      };
      reader.readAsDataURL(file); // Konversi ke Base64
    }
  }
  
  addProduct() {
    console.log('Data dikirim:', this.productForm.value);
    if (this.productForm.valid) {
      this.http.post(this.apiUrl, this.productForm.value).subscribe(
        (response: any) => {
          console.log('Respons server:', response);
          if (response.status === 'success') {
            alert('Produk berhasil ditambahkan!');
            this.router.navigate(['/admin/product']).then(() => {
              window.location.reload(); // Paksa refresh supaya gambar langsung muncul
            });
          } else {
            alert('Gagal menambahkan produk.');
          }
        },
        (error) => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat mengirim data.');
        }
      );
    } else {
      alert('Harap isi semua field.');
    }
  }
  
  
}