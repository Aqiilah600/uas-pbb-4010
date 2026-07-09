import 'package:dio/dio.dart';
import '../../../core/constants/api_constants.dart';
import '../../../core/network/dio_client.dart';
import '../../../core/network/secure_storage_service.dart';

class AuthRepository {
  final DioClient _dioClient = DioClient();
  final SecureStorageService _storageService = SecureStorageService();

  Future<String> login(String email, String password) async {
    try {
      final response = await _dioClient.dio.post(
        ApiConstants.login,
        data: {'email': email, 'password': password},
      );

      final token = response.data['token'] as String;
      await _storageService.saveToken(token);
      return token;
    } on DioException catch (e) {
      if (e.response?.statusCode == 401) {
        throw Exception('Email atau password salah.');
      }
      throw Exception('Gagal terhubung ke server. Coba lagi.');
    }
  }

  Future<Map<String, dynamic>> getProfile() async {
    final response = await _dioClient.dio.get(ApiConstants.me);
    return response.data;
  }

  Future<void> logout() async {
    try {
      await _dioClient.dio.post(ApiConstants.logout);
    } catch (_) {
      // Tetap lanjut hapus token lokal walau request logout ke server gagal
    }
    await _storageService.deleteToken();
  }
}
