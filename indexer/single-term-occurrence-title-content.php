<?php
/*
 * Plugin Name: SearchWP - Limit Content to Single Term Occurrence
 * Description: Only consider one occurrence of each keyword in titles and main content (no duplicates)
 * Author: Jonathan Christopher
 * Version: 1.0
 */

/**
 * Modify SearchWP's behavior to index only single occurrences of words in titles and content
 *
 * @param $content
 *
 * @return array|string
 */
class My_SearchWP_Single_Term_Occurrence_Title_Content {

	// filter titles?
	private $filter_titles = true;

	// filter content?
	private $filter_content = true;

	function __construct() {
		add_filter( 'searchwp_set_post', array( $this, 'set_post' ) );
	}

	/**
	 * Callback for SearchWP's searchwp_set_post filter that allows us to filter the post object
	 * being indexed before it gets indexed
	 *
	 * @param $post_being_indexed
	 *
	 * @return mixed
	 */
	function set_post( $post_being_indexed ) {

		// remove duplicate words from title
		if ( $this->filter_titles ) {
			$post_being_indexed->post_title = $this->remove_duplicate_words_from_string( $post_being_indexed->post_title );
		}

		// remove duplicate words from content
		if ( $this->filter_content ) {
			$post_being_indexed->post_content = $this->remove_duplicate_words_from_string( $post_being_indexed->post_content );
		}

		return $post_being_indexed;
	}

	/**
	 * Remove duplicate words from a string
	 *
	 * @param $content
	 *
	 * @return array|string
	 */
	function remove_duplicate_words_from_string( $content ) {
		if ( ! class_exists( 'SearchWPIndexer' ) ) {
			return $content;
		}

		$searchwp_indexer = new SearchWPIndexer();

		// standardize the content
		$content = $searchwp_indexer->clean_content( $content );
		$content = explode( ' ', $content );
		$content = array_map( 'trim', $content );

		// remove the dupes
		$content = array_unique( $content );

		// put it back as a string
		$content = implode( ' ', $content );

		return $content;
	}
}

new My_SearchWP_Single_Term_Occurrence_Title_Content();
