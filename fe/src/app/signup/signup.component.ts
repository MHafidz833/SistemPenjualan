import { Component } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-signup',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './signup.component.html',
  styleUrl: './signup.component.css'
})
export class SignupComponent {
  nama: string = '';
  username: string = '';
  email: string = '';
  password: string = '';

  constructor(private http: HttpClient) {}

  signup(): void {
    const body = new URLSearchParams();
    body.set('nama', this.nama);
    body.set('username', this.username);
    body.set('email', this.email);
    body.set('password', this.password);

    const headers = new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' });

    this.http.post('http://localhost/mahiahijab/api/auth/signup.php', body.toString(), { headers })
      .subscribe(
        (response: any) => {
          if (response.status === 'success') {
            alert(response.message);
            window.location.href = '/login';
          } else {
            alert(response.message);
          }
        },
        error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat signup.');
        }
      );
  }
}
