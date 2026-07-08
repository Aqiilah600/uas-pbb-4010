import 'package:dio/dio.dart';
import '../constants/api_constants.dart';
import 'secure_storage_service.dart';

class DioClient {
  final Dio dio;
  final SecureStorageService _storageService = SecureStorageService();

  DioClient()
      : dio = Dio(BaseOptions(
          baseUrl: ApiConstants.baseUrl,
          connectTimeout: const Duration(seconds: 10),
          receiveTimeout: const Duration(seconds: 10),
        )) {
    dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await _storageService.getToken();
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
      ),
    );
  }
}