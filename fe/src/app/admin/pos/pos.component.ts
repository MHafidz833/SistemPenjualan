import { Component, OnInit } from '@angular/core';
import { PostService } from './post.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { SidebarComponent } from '../left-sidebar/left-sidebar.component';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'admin-app-pos',
  imports: [CommonModule, FormsModule, RouterModule, SidebarComponent],
  templateUrl: './pos.component.html',
  styleUrl: './pos.component.css'
})
export class PosComponent implements OnInit {
  posts: any[] = [];
  isLoggedIn: boolean = false; // Menambahkan indikator login

  constructor(private postService: PostService, public router: Router, private http: HttpClient) { }

  ngOnInit() {
    this.checkLogin();
  }

  checkLogin(): void {
    this.http.get<any>('http://localhost/mahiahijab/api/admin/auth/login.php', { withCredentials: true })
      .subscribe({
        next: (response) => {
          if (response.status === 'success') {
            this.isLoggedIn = true; // Tandai bahwa pengguna telah login
            this.loadPosts(); // Hanya panggil loadPosts jika sudah login
          } else {
            this.redirectToLogin();
          }
        },
        error: (err) => {
          console.error('Gagal memeriksa sesi', err);
          this.redirectToLogin();
        }
      });
  }

  redirectToLogin(): void {
    alert('Silakan login terlebih dahulu!');
    this.router.navigate(['/admin/login']);
  }

  loadPosts() {
    if (!this.isLoggedIn) {
      console.log('Pengguna belum login, data tidak akan dimuat.');
      return; // Cegah pemuatan data jika belum login
    }

    this.postService.getPosts().subscribe(
      (response) => {
        if (response.status === 'success') {
          this.posts = response.data;
        } else {
          console.error('Error fetching posts:', response.message);
        }
      },
      (error) => {
        console.error('Error loading posts', error);
      }
    );
  }

  deletePost(id: number) {
    if (confirm('Apakah Anda yakin ingin menghapus postingan ini?')) {
      this.postService.deletePost(id).subscribe(
        (response) => {
          if (response.status === 'success') {
            alert('Postingan berhasil dihapus');
            this.loadPosts();
          } else {
            alert('Gagal menghapus postingan: ' + response.message);
          }
        },
        (error) => {
          console.error('Error deleting post', error);
          alert('Gagal menghapus postingan');
        }
      );
    }
  }

  onTambahpos(): void {
    this.router.navigate(['/admin/tambah-pos']).then(() => {
      console.log('Navigasi ke halaman tambah pos');
      window.location.reload();
    });
  }

  onCategory(): void {
    this.router.navigate(['/admin/kategori-pos']).then(() => {
      console.log('Navigasi ke halaman kategori pos');
      window.location.reload();
    });
  }
}
