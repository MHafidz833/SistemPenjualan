import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';

@Injectable({
    providedIn: 'root', // Pastikan service ini bersifat global
})
export class LogoutService {
    constructor(private http: HttpClient, private router: Router) {}

    logout(): void {
        this.http.get('http://localhost/mahiahijab/api/admin/auth/logout.php', { withCredentials: true })
            .subscribe(() => {
                localStorage.clear();
                sessionStorage.clear();
                this.router.navigate(['/admin/login']).then(() => {
                    window.location.reload(); // Refresh halaman login
                });
            }, error => {
                console.error('Logout error:', error);
                alert('Gagal logout dari server!');
                localStorage.clear();
                sessionStorage.clear();
                this.router.navigate(['/admin/login']);
            });
    }
}
