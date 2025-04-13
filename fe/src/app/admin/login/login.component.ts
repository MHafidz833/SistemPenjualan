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
export class AdminLoginComponent {
    u: string = '';
    p: string = '';

    constructor(private http: HttpClient, private router: Router) {}

    onLogin(): void {
        const body = new URLSearchParams();
        body.set('u', this.u);
        body.set('p', this.p);

        const headers = new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded' });

        this.http.post<any>('http://localhost/mahiahijab/api/admin/auth/login.php', body.toString(), {
            headers,
            withCredentials: true  // Penting agar cookie `PHPSESSID` terbawa
        }).subscribe(
            response => {
                if (response.status === 'success') {
                    alert('Login berhasil!');
                    this.router.navigate(['/admin/dashboard']);
                } else {
                    alert('Login gagal: ' + response.message);
                }
            },
            error => {
                alert('Gagal menghubungi server!');
                console.error('Login error:', error);
            }
        );
    }
}
