<?php // phpcs:ignore
/**
 * Filesystem helper class
 *
 * @package snapshot
 */

namespace WPMUDEV\Snapshot4\Helper;

/**
 * Helper class
 */
class Fs {

	/**
	 * Resolves relative template path to an actual absolute path
	 *
	 * @return string
	 */
	public static function get_root_path() {
		$home_path = defined( 'ABSPATH' ) ? ABSPATH : get_home_path(); // with fallback.

		// Flywheel fix.
		if ( defined( 'FLYWHEEL_CONFIG_DIR' ) ) {
			$home_path = dirname( WP_CONTENT_DIR );
		}

		return trailingslashit( wp_normalize_path( apply_filters( 'wp_snapshot_home_path', $home_path ) ) );
	}

	/**
	 * Creates an empty index file for security purposes
	 *
	 * @return string
	 */
	public static function add_index_file( $index_pathname ) {
		if ( ! file_exists( $index_pathname ) ) {
			$file = fopen( $index_pathname, 'w' );
			if ( false === $file ) {
				return;
			}
			fwrite( $file, "<?php\n// Silence is golden." );
			fclose( $file );
		}
	}

	/**
	 * List all the files and folders in a given path.
	 *
	 * @since 4.13
	 *
	 * @param string $path Path to list the files and folders.
	 *
	 * @return array
	 */
	public static function list( $path ) {
		$iterables = ( new Iterables( $path ) )->get_iterables();

		$files = array();
		$c = 0;
		foreach ( $iterables as $it ) {
			$path_name = $it->getPathName();

			$files[$c] = array(
				'type' => $it->getType(),
				'name' => $it->getBaseName(),
				'path' => false !== strpos( $path_name, self::get_root_path() ) ? DIRECTORY_SEPARATOR . str_replace( self::get_root_path(), '', $path_name ) : $path_name,
				'size' => self::format_size( $it->getSize() ),
			);

			if ( $it->isDir() ) {
				$files[$c]['browsable'] = ( new Iterables( $path_name ) )->browsable();
			}

			$c++;
		}

		return $files;
	}

	/**
	 * Sort the files based on directory and then files.
	 *
	 * @since 4.13
	 *
	 * @param array $files List of un-altered files.
	 *
	 * @return array
	 */
	public static function sort( $files ) {
		$dirs = array_filter( $files, function($file) use ($files) {
			return 'dir' === $file['type'];
		});

		usort( $dirs, function( $a, $b ) {
			return strcasecmp( $a['name'], $b['name'] );
		});


		$only_files = array_filter( $files, function( $file ) {
			return 'dir' !== $file['type'];
		} );

		usort( $only_files, function( $a, $b ) {
			return strcasecmp( $a['name'], $b['name'] );
		});

		return array_merge( $dirs, $only_files );
	}

	/**
	 * Get the formatted size.
	 *
	 * @since 4.13
	 *
	 * @param integer $size Size in byte.
	 * @param integer $precision Floating point decimal place.
	 *
	 * @return string
	 */
	public static function format_size( int $size, int $precision = 2 ) {
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );
		$bytes = max( $size, 0 );
		$pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
		$pow   = min( $pow, count( $units ) - 1 );

		$bytes /= pow( 1024, $pow );

		return round( $bytes, $precision ) . ' ' . $units[$pow];
	}


	/**
	 * Get the file contents
	 *
	 * @param string $file Path to the file.
	 *
	 * @return string
	 */
	public static function get_file_contents( $file ): string {
		$content = file_get_contents( $file );
		return $content ?? '';
	}
}