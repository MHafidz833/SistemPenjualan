import { Component } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  standalone: true,
  imports: [FormsModule, CommonModule]
})
export class LoginComponent {
  u: string = '';
  p: string = '';

  constructor(private http: HttpClient, public router: Router) { } // Router diubah ke public

  onLogin() {
    const body = new URLSearchParams();
    body.set('u', this.u);
    body.set('p', this.p);

    const headers = new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' });

    this.http.post<any>('http://localhost/mahiahijab/api/auth/login.php', body.toString(), { headers })
      .subscribe(
        response => {
          if (response.status === 'success') {
            alert('Login Berhasil');
            localStorage.setItem('user', JSON.stringify(response.pelanggan));
            localStorage.setItem('isLogin', 'true');
            this.router.navigate(['/']);
          } else {
            alert('Login Gagal! Username atau Password salah');
          }
        },
        error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan, coba lagi nanti.');
        }
      );
  }
}
