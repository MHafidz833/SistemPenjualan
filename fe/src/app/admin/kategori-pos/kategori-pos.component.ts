import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CategoryService } from './category.service';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';

@Component({
  selector: 'admin-app-kategori-pos',
  imports: [CommonModule, FormsModule, SidebarComponent],
  templateUrl: './kategori-pos.component.html',
  styleUrl: './kategori-pos.component.css'
})
export class AdminKategoriPosComponent implements OnInit {
  categories: any[] = [];
  newCategory: string = '';

  constructor(public categoryService: CategoryService) { }

  ngOnInit() {
    this.loadCategories();
  }

  loadCategories() {
    this.categoryService.getCategories().subscribe(
      (response) => {
        if (response.status === 'success') {
          this.categories = response.data;
        } else {
          console.error('Error fetching categories:', response.message);
        }
      },
      (error) => {
        console.error('Error loading categories', error);
      }
    );
  }

  addCategory() {
    const categoryName = this.newCategory.trim();
    if (categoryName === '') {
      alert('Nama kategori tidak boleh kosong');
      return;
    }

    this.categoryService.addCategory(categoryName).subscribe(
      (response) => {
        if (response.status === 'success') {
          alert('Kategori berhasil ditambahkan');
          this.newCategory = '';
          this.loadCategories();
        } else {
          alert('Gagal menambahkan kategori: ' + response.message);
        }
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
        (response) => {
          if (response.status === 'success') {
            alert('Kategori berhasil dihapus');
            this.loadCategories();
          } else {
            alert('Gagal menghapus kategori: ' + response.message);
          }
        },
        (error) => {
          console.error('Error deleting category', error);
          alert('Gagal menghapus kategori');
        }
      );
    }
  }
}