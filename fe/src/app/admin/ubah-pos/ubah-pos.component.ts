import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';
import { PosService } from './pos.service';

@Component({
  selector: 'admin-app-ubah-pos',
  imports: [ReactiveFormsModule, FormsModule, CommonModule, SidebarComponent],
  templateUrl: './ubah-pos.component.html',
  styleUrl: './ubah-pos.component.css'
})
export class AdminUbahPosComponent implements OnInit {
  editForm!: FormGroup;
  categories: any[] = [];
  postId!: number;
  imagePreview: string | null = null;
  base64Image: string | null = null; // Simpan gambar dalam format Base64

  constructor(
    private fb: FormBuilder,
    private posService: PosService,
    private route: ActivatedRoute,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.postId = Number(this.route.snapshot.paramMap.get('id'));

    this.editForm = this.fb.group({
      judul: [''],
      isi: [''],
      kategori: [''],
      gambar: ['']
    });

    this.getCategories();
  }

  getCategories(): void {
    this.posService.getCategories().subscribe(
      (response) => {
        if (response.status === 'success') {
          this.categories = response.data;
        }
      },
      (error) => console.error('Gagal mengambil kategori', error)
    );
  }

  onFileSelected(event: any): void {
    const file = event.target.files[0];

    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        this.base64Image = (reader.result as string); // Simpan dalam Base64 tanpa prefix data:image
        this.imagePreview = reader.result as string;
      };
      reader.readAsDataURL(file);
    }
  }

  onSubmit(): void {
    const postData = {
      id: this.postId.toString(),
      judul: this.editForm.value.judul,
      isi: this.editForm.value.isi,
      kategori: this.editForm.value.kategori,
      gambar: this.base64Image // Kirim gambar dalam format Base64
    };
  
    console.log(postData);
  
    this.posService.updatePost(postData).subscribe(
      (response) => {
        console.log(response);
        alert('Postingan berhasil diubah!');
        this.router.navigate(['/admin/pos']); // Navigasi otomatis ke halaman /admin/pos
      },
      (error) => console.error('Gagal mengubah postingan', error)
    );
  }
  
}
