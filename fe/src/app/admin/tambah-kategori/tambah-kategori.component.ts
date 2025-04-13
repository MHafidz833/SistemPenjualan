import { Component, OnInit } from '@angular/core';
import { CategoryService } from './category.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';

@Component({
  selector: 'admin-app-tambah-kategori',
  imports: [CommonModule, FormsModule, SidebarComponent],
  templateUrl: './tambah-kategori.component.html',
  styleUrl: './tambah-kategori.component.css'
})
export class AdminTambahKategoriComponent implements OnInit {
  categories: any[] = [];
  name: string = '';

  constructor(private categoryService: CategoryService) { }

  ngOnInit() {
    this.loadCategories();
  }

  loadCategories() {
    this.categoryService.getCategories().subscribe(
      (data) => {
        this.categories = data;
      },
      (error) => {
        console.error('Error loading categories', error);
      }
    );
  }

  addCategory() {
    const name = this.name.trim();
    if (name === '') {
      alert('Nama kategori tidak boleh kosong');
      return;
    }

    this.categoryService.addCategory(name).subscribe(
      (response) => {
        console.log('Response dari API:', response); // Debugging
        alert('Kategori berhasil ditambahkan');
        this.name = '';
        this.loadCategories(); // Refresh data setelah menambah
      },
      (error) => {
        console.error('Error adding category', error);
        alert('Gagal menambahkan kategori');
      }
    );
  }

  deleteCategory(id: number) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
      this.categoryService.deleteCategory(id).subscribe(
        () => {
          alert('Kategori berhasil dihapus');
          this.loadCategories(); // Refresh data
        },
        (error) => {
          console.error('Error deleting category', error);
        }
      );
    }
  }
}
