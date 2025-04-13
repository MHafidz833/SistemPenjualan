import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
    providedIn: 'root',
})
export class LogoutService {
    constructor(private router: Router) { }

    logout(): void {
        // Hapus semua data di local storage dan session storage
        localStorage.clear();
        sessionStorage.clear();

        // Redirect ke halaman login
        this.router.navigate(['/']).then(() => {
            // Auto refresh halaman setelah redirect
            window.location.reload();
        });
    }
}
