import { Component } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';  // ✅ Import FormsModule

@Component({
  selector: 'app-lupa-password',
  standalone: true,         // ✅ Kalau pakai standalone, perlu import di sini
  imports: [CommonModule, FormsModule],   // ✅ Tambah FormsModule
  templateUrl: './lupa-password.component.html',
  styleUrl: './lupa-password.component.css'
})
export class LupaPasswordComponent {
  email: string = '';
  password: string = '';
  successMessage: string = '';

  constructor(private http: HttpClient) { }

  signup(): void {
    const body = new URLSearchParams();
    body.set('email', this.email);
    body.set('password', this.password);

    const headers = new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' });

    this.http.post('http://localhost/mahiahijab/api/auth/lupa-password.php', body.toString(), { headers })
      .subscribe(
        (response: any) => {
          if (response.status === 'success') {
            this.successMessage = response.message;
            setTimeout(() => {
              window.location.href = '/login';
            }, 1000);
          } else {
            alert(response.message);
          }
        },
        error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat reset password.');
        }
      );
  }
}
