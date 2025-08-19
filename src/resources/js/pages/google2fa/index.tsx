import AuthLayout from '@/layouts/auth-layout';
import { Head } from '@inertiajs/react';


export default function Google2FAPage() {
    return (
        <AuthLayout title="Register 2FA" description="Add MFA device">
            <Head title="Register" />
            { /* TODO: Create an endpoint /google2fa/qr, get and display the QR code */ }
        </AuthLayout>
    );
}
