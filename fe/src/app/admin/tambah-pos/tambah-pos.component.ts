import { Component, OnInit } from '@angular/core';
import { PosService } from './post.service';
import { AbstractControl, FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { RouterModule, Router } from '@angular/router';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';

@Component({
  selector: 'admin-app-tambah-pos',
  imports: [CommonModule, FormsModule, RouterModule, ReactiveFormsModule, SidebarComponent],
  templateUrl: './tambah-pos.component.html',
  styleUrl: './tambah-pos.component.css',
  standalone: true,
})
export class AdminTambahPosComponent implements OnInit {
  postForm!: FormGroup;
  categories: any[] = [];
  base64Image: string = '';

  constructor(private posService: PosService, private fb: FormBuilder, private router: Router) { }

  ngOnInit(): void {
    this.postForm = this.fb.group({
      judul: ['', Validators.required],
      isi: ['', Validators.required],
      kategori: ['', Validators.required],
      gambar: ['', this.base64Validator],
    });
    this.loadCategories();
  }

  base64Validator(control: AbstractControl) {
    const value = control.value;
    if (!value || !value.startsWith('data:image/')) {
      return { invalidBase64: true };
    }
    return null;
  }

  loadCategories() {
    this.posService.getCategories().subscribe(response => {
      if (response.status === 'success') {
        this.categories = response.data;
      } else {
        console.error('Gagal mengambil kategori');
      }
    });
  }

  convertToBase64(event: Event) {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = () => {
        this.base64Image = reader.result as string;
        this.postForm.controls['gambar'].setValue(this.base64Image);
        this.postForm.controls['gambar'].updateValueAndValidity();
      };
      reader.readAsDataURL(file);
    }
  }

  submitPost() {
    if (this.postForm.invalid) {
      alert('Form masih tidak valid!');
      return;
    }

    if (this.base64Image == "") {
      alert('Harap upload gambar terlebih dahulu!');
      return;
    }

    const postData = {
      judul: this.postForm.get('judul')?.value,
      isi: this.postForm.get('isi')?.value,
      kategori: this.postForm.get('kategori')?.value,
      gambar: this.base64Image
    };

    this.posService.addPost(postData).subscribe(response => {
      if (response.status === 'success') {
        alert('Postingan berhasil ditambahkan!');
        this.postForm.reset();
        this.base64Image = '';
        this.router.navigate(['/admin/pos']);
      } else {
        alert('Gagal menambahkan postingan.');
      }
    });
  }
}