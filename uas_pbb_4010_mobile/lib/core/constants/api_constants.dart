class ApiConstants {
  // Emulator Android: gunakan 10.0.2.2 (bukan localhost/127.0.0.1)
  // Kalau pakai device fisik: ganti dengan IP komputer kamu, misal http://192.168.1.10:8000/api
  // Kalau pakai Chrome/web atau iOS simulator: bisa pakai http://127.0.0.1:8000/api
  static const String baseUrl = 'http://127.0.0.1:8000/api';

  static const String login = '/login';
  static const String logout = '/logout';
  static const String me = '/me';
  static const String mahasiswa = '/mahasiswa';
}
